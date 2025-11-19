<?php

// Requerir el archivo de conexión (Ajusta la ruta si es necesario)
require_once dirname(__DIR__, 2) . '/Conexion.php'; 

class Model_Publicacion
{
    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->getConnection();
    }


    public function getConnection() {
    return $this->conn;
}

    /**
     * Registra una nueva publicación y su multimedia asociada.
     * @param string $titulo Título de la publicación.
     * @param string $descripcion Descripción/cuerpo del post.
     * @param array $fileData Array asociativo de $_FILES['nombre_del_campo'].
     * @param int $autorId ID del usuario que crea la publicación.
     * @param int|null $mundialId ID del mundial asociado (puede ser NULL).
     * @param int $categoriaId ID de la categoría.
     * @return array Retorna ['success' => bool, 'message' => string, 'publicacion_id' => int|null]
     */
    public function registrarPublicacion(
        string $titulo, 
        string $descripcion, 
        array $fileData, 
        int $autorId, 
        ?int $mundialId, 
        int $categoriaId
    ): array
    {
        try {
            // Validación de archivo básico (basado en el SP, debe existir)
            if (!isset($fileData['tmp_name']) || $fileData['error'] !== UPLOAD_ERR_OK) {
                return [
                    'success' => false,
                    'message' => 'Debe subir un archivo (imagen o video).'
                ];
            }

            // Extracción de datos para el SP
            $nombreArchivo = $fileData['name'];
            $tipoMime      = $fileData['type'];
            // Obtener el contenido del archivo temporal (BLOB)
            $contenido     = file_get_contents($fileData['tmp_name']); 

            if ($contenido === false) {
                 return [
                    'success' => false,
                    'message' => 'ERROR: No se pudo leer el contenido del archivo.'
                ];
            }

            // Preparar la llamada al Stored Procedure
            $stmt = $this->conn->prepare("
                CALL sp_registrar_publicacion_con_multimedia(
                    :p_titulo,
                    :p_descripcion,
                    :p_nombre_archivo,
                    :p_tipo_mime,
                    :p_contenido,
                    :p_autor_id,
                    :p_mundial_id,
                    :p_categoria_id
                )
            ");

            // 1. Bind de Parámetros de Texto (PARAM_STR)
            $stmt->bindParam(':p_titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':p_descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':p_nombre_archivo', $nombreArchivo, PDO::PARAM_STR);
            $stmt->bindParam(':p_tipo_mime', $tipoMime, PDO::PARAM_STR);
            $stmt->bindParam(':p_autor_id', $autorId, PDO::PARAM_INT);
            $stmt->bindParam(':p_categoria_id', $categoriaId, PDO::PARAM_INT);
            
            // 2. Bind del BLOB (PARAM_LOB)
            $stmt->bindParam(':p_contenido', $contenido, PDO::PARAM_LOB); 

            // 3. Bind para Mundial ID (manejo de NULL)
            if ($mundialId === null || $mundialId <= 0) {
                $stmt->bindValue(':p_mundial_id', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':p_mundial_id', $mundialId, PDO::PARAM_INT);
            }
            
            $stmt->execute();

            // El SP devuelve un SELECT con el resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Importante: Cierra el cursor para liberar el SP
            $stmt->closeCursor();

            // Devolver el resultado del SP
            return [
                'success' => $result['result'] === 'success',
                'message' => $result['message'],
                'publicacion_id' => $result['publicacion_id'] ?? null
            ];

        } catch (PDOException $e) {
           $db_error_message = $e->getMessage();
            // error_log ya tiene el mensaje real, pero lo dejamos
            error_log('Error PDO en Model_Publicacion::registrarPublicacion: ' . $db_error_message);
            
            return [
                'success' => false,
                // ¡IMPORTANTE! Devolvemos el mensaje detallado de la excepción
                'message' => 'Error de BD: ' . $db_error_message
            ];
        } catch (Exception $e) {
            error_log('Error fatal en Model_Publicacion: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error inesperado del servidor.'
            ];
        }
        

    }
    
    public function obtenerPublicacionesPendientes(): array
    {
        try {
            // Utilizamos la vista v_publicaciones_pendientes
            $stmt = $this->conn->prepare("SELECT * FROM v_publicaciones_pendientes");
            $stmt->execute();
            
            // Retorna todas las filas encontradas
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // Loguea el error de la base de datos
            error_log('Error PDO al consultar publicaciones pendientes: ' . $e->getMessage());
            // Devuelve un array vacío para evitar errores en el controlador/vista
            return []; 
        }
    }

    public function actualizarAprobacionPublicacion(int $id, int $decision): array
    {
        try {
            $stmt = $this->conn->prepare("
                CALL sp_actualizar_aprobacion_publicacion(:p_id, :p_decision)
            ");

            $stmt->bindParam(':p_id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':p_decision', $decision, PDO::PARAM_INT);

            $stmt->execute();

            // Primer resultset: respuesta del SP
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // IMPORTANTE: limpiar result sets extra que genera MySQL
            while ($stmt->nextRowset()) { /* limpiar */ }

            $stmt->closeCursor();

            return [
                'success' => isset($result['result']) && $result['result'] === 'success',
                'message' => $result['message'] ?? 'Operación realizada correctamente.'
            ];
        }
        catch (PDOException $e) {
            error_log("Error en actualizarAprobacionPublicacion: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error de BD: ' . $e->getMessage()
            ];
        }
    }

    public function obtenerPublicacionesPorMundialAprobadas(int $mundialId): array
    {
        try {
            // Consulta para obtener publicaciones aprobadas (estado_aprobacion = 1) por Mundial ID.
            // Se asume que 'publicacion', 'usuario' y 'categoria' son las tablas.
            $sql = "
        SELECT
            id,
            titulo,
            descripcion,
            nombre_categoria,
            nombre_autor_completo,
            fecha_publicacion,
            nombre_archivo,
            tipo_mime,
            base64_multimedia
        FROM
            vw_publicaciones_completas
        WHERE
            mundial_id = :p_mundial_id
            AND AprobadoAdmin = 1
        ORDER BY
            fecha_publicacion DESC
    ";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':p_mundial_id', $mundialId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log('Error PDO al consultar publicaciones por Mundial (Aprobadas): ' . $e->getMessage());
            return []; 
        }
    }

   public function obtenerPublicacionesUsuarioAprobadas($id_usuario)
{
    $sql = "SELECT 
                id, 
                titulo, 
                descripcion, 
                fecha_publicacion, 
                estatus, 
                categoria_id, 
                mundial_id, 
                nombre_archivo, 
                tipo_mime, 
                multimedia_base64
            FROM vw_publicaciones_con_multimedia
            WHERE autor_id = :id_usuario
              AND estatus = 'aprobada'
            ORDER BY fecha_publicacion DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();



    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function toggleLike($id_usuario, $id_publicacion)
{
    // Asegurarse de que la publicación esté aprobada
    $sqlCheck = "SELECT id FROM Publicacion WHERE id = ? AND ESTATUS = 'aprobada' AND AprobadoAdmin = 1";
    $stmt = $this->conn->prepare($sqlCheck);
    $stmt->execute([$id_publicacion]);
    if (!$stmt->fetch()) {
        return "invalid"; // Publicación no aprobada
    }

    // Revisar si ya existe like
    $sql = "SELECT 1 FROM Interacciones WHERE id_usuario = ? AND id_publicacion = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id_usuario, $id_publicacion]);

    if ($stmt->fetch()) {
        // Ya había like → lo quitamos
        $sql = "DELETE FROM Interacciones WHERE id_usuario = ? AND id_publicacion = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_usuario, $id_publicacion]);
        return "removed";
    } else {
        // No había → lo agregamos
        $sql = "INSERT INTO Interacciones (id_usuario, id_publicacion) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_usuario, $id_publicacion]);
        return "added";
    }
}
public function contarLikes($id_publicacion)
{
    $sql = "SELECT COUNT(*) AS total_likes FROM Interacciones WHERE id_publicacion = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id_publicacion]);

    return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total_likes'];
}
public function usuarioDioLike($id_usuario, $id_publicacion)
{
    $sql = "SELECT 1 FROM Interacciones WHERE id_usuario = ? AND id_publicacion = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id_usuario, $id_publicacion]);

    return (bool)$stmt->fetch();
}


// En Model_Publicacion.php (Añadir o modificar el método)

public function buscarPublicaciones(array $filtros): array
{
    $sql = "
        SELECT 
            p.id, p.titulo, p.descripcion, p.fecha_publicacion, m.titulo AS nombre_mundial,
            CONCAT(u.NOMBRES, ' ', u.APELLIDO_P) AS nombre_autor_completo, 
            c.nombre AS nombre_categoria, p.base64_multimedia
        FROM 
            vw_publicaciones_completas p
        JOIN usuario u ON p.autor_id = u.ID_USUARIO
        JOIN categorias c ON p.categoria_id = c.id
        LEFT JOIN mundial m ON p.mundial_id = m.id
        WHERE 
            p.AprobadoAdmin = 1 
    ";
    
    $parametros = [];

    // Filtro 1: Palabra clave (Título o Descripción)
    if (!empty($filtros['q'])) {
        $sql .= " AND (p.titulo LIKE :termino OR p.descripcion LIKE :termino)";
        $termino_like = '%' . $filtros['q'] . '%';
        $parametros[':termino'] = $termino_like;
    }

    // Filtro 2: Rango de Fechas
    if (!empty($filtros['desde']) && !empty($filtros['hasta'])) {
        $sql .= " AND p.fecha_publicacion BETWEEN :desde AND :hasta";
        $parametros[':desde'] = $filtros['desde'];
        // Ajustamos la fecha "hasta" para incluir todo el día
        $parametros[':hasta'] = $filtros['hasta'] . ' 23:59:59'; 
    }

    // Filtro 3: Categoría
    if (!empty($filtros['categoria_id'])) {
        $sql .= " AND c.id = :categoria_id";
        $parametros[':categoria_id'] = (int)$filtros['categoria_id'];
    }

    // Filtro 4: Autor (Usuario)
    if (!empty($filtros['usuario_id'])) {
        $sql .= " AND u.ID_USUARIO = :usuario_id";
        $parametros[':usuario_id'] = (int)$filtros['usuario_id'];
    }
    
    $sql .= " ORDER BY p.fecha_publicacion DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($parametros);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}