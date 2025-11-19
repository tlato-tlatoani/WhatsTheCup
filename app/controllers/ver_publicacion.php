<?php


ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once PROJECT_ROOT . '/app/models/Model_Publicacion.php';
require_once PROJECT_ROOT . '/app/models/Model_Interaccion.php';
require_once PROJECT_ROOT . '/app/models/Model_Comentario.php';


$id_publicacion = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$publicacion = null;
$error_publicacion = null;

// Inicialización de variables para la vista
$likes_actuales = 0;
$usuario_ya_dio_like = false;
$comentarios_actuales = 0; 
$comentarios_activos = []; // <--- Inicializar como array vacío para seguridad 

if (!$id_publicacion) {
    $error_publicacion = "ID de publicación no válido.";
} else {
    try {
        $model = new Model_Publicacion();

        // Consulta especial para obtener UNA publicación aprobada con multimedia
        // En /app/controllers/ver_publicacion.php

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
                    vw_publicacion_detalles
                WHERE 
                    id = :p_id
                    AND AprobadoAdmin = 1
                LIMIT 1
            ";


        $conn = $model->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':p_id', $id_publicacion, PDO::PARAM_INT);
        $stmt->execute();

        $publicacion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$publicacion) {
            $error_publicacion = "La publicación no existe o no está aprobada.";
        }

        else {
           
            $modelInteraccion = new Model_Interaccion();
            $modelComentario = new Model_Comentario();

            // Obtener el ID del usuario logueado desde la sesión
            $usuarioId = $_SESSION['id_usuario'] ?? null; 
            $publicacionId = $publicacion['id'];

            // 1. Contar likes
            $likes_actuales = $modelInteraccion->contarLikes($publicacionId);
            $comentarios_actuales = $modelComentario->contarComentarios($publicacionId);

            $comentarios_activos = $modelComentario->obtenerComentariosPorPublicacion($publicacionId);

            // 2. Verificar estado del like (solo si hay usuario logueado)
            if ($usuarioId) {
                $usuario_ya_dio_like = $modelInteraccion->verificarLike($usuarioId, $publicacionId);
            }
        }

    } catch (Exception $e) {
        error_log("Error al cargar publicación individual: " . $e->getMessage());
        $error_publicacion = "Error en el servidor al cargar la publicación.";
    }
}
