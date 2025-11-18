<?php

require_once dirname(__DIR__, 2) . '/Conexion.php';

class Model_Publicacion
{
    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->getConnection();
    }

    /**
     * Registrar publicación con multimedia (imagen o video obligatorio)
     */
    public function registrarPublicacion($titulo, $descripcion, $fileData, $autorId, $mundialId, $categoriaId)
    {
        try {

            // Validar archivo obligatorio
            if (!$fileData || $fileData['error'] !== UPLOAD_ERR_OK) {
                return [
                    'success' => false,
                    'message' => 'Debe seleccionar una imagen o video para subir.'
                ];
            }

            // Datos del archivo
            $nombreArchivo = $fileData['name'];
            $tipoMime      = $fileData['type'];
            $contenido     = file_get_contents($fileData['tmp_name']); // BLOB


            if ($contenido === false) {
                 return [
                    'success' => false,
                    'message' => 'ERROR FATAL: No se pudo leer el contenido del archivo. (Posiblemente demasiado grande o sin permisos)'
                ];
            }

            
            // Llamada al stored procedure
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

            // Bind params
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':nombre_archivo', $nombreArchivo, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_mime', $tipoMime, PDO::PARAM_STR);
            $stmt->bindParam(':contenido', $contenido, PDO::PARAM_LOB);
            $stmt->bindParam(':autor_id', $autorId, PDO::PARAM_INT);
            $stmt->bindParam(':mundial_id', $mundialId, PDO::PARAM_INT);
            $stmt->bindParam(':categoria_id', $categoriaId, PDO::PARAM_INT);

            $stmt->execute();

            // El SP devuelve un SELECT con estado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Limpiar múltiples resultsets del SP
            $stmt->closeCursor();

            return [
                'success' => $result['result'] === 'success',
                'message' => $result['message'],
                'publicacion_id' => $result['publicacion_id'] ?? null
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage() 
                    . (isset($stmt) ? ' | SQLSTATE: ' . ($stmt->errorInfo()[0] ?? 'N/A') . ' | Driver Message: ' . ($stmt->errorInfo()[2] ?? 'N/A') : '')
            ];
        }
    }
}

?>
