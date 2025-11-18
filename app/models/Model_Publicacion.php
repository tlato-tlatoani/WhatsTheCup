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
                    :titulo,
                    :descripcion,
                    :nombre_archivo,
                    :tipo_mime,
                    :contenido,
                    :autor_id,
                    :mundial_id,
                    :categoria_id
                )
            ");

            // 1. Bind de Parámetros de Texto (PARAM_STR)
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':nombre_archivo', $nombreArchivo, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_mime', $tipoMime, PDO::PARAM_STR);
            $stmt->bindParam(':autor_id', $autorId, PDO::PARAM_INT);
            $stmt->bindParam(':categoria_id', $categoriaId, PDO::PARAM_INT);
            
            // 2. Bind del BLOB (PARAM_LOB)
            $stmt->bindParam(':contenido', $contenido, PDO::PARAM_LOB); 

            // 3. Bind para Mundial ID (manejo de NULL)
            if ($mundialId === null || $mundialId <= 0) {
                $stmt->bindValue(':mundial_id', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':mundial_id', $mundialId, PDO::PARAM_INT);
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
            error_log('Error PDO en Model_Publicacion::registrarPublicacion: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error de Base de Datos al registrar.'
            ];
        } catch (Exception $e) {
            error_log('Error fatal en Model_Publicacion: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error inesperado del servidor.'
            ];
        }
    }
    
    // Aquí irían otros métodos del modelo (consultarPublicaciones, etc.)
}