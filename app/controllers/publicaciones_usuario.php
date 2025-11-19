<?php


require_once PROJECT_ROOT . '/app/models/Model_Publicacion.php';
require_once PROJECT_ROOT . '/app/models/Model_Interaccion.php';
require_once PROJECT_ROOT . '/app/models/Model_Comentario.php';


// Iniciar sesión para obtener el ID del usuario
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['id_usuario'] ?? null;

// Variables para la vista
$publicaciones_usuario = [];
$error_consulta = null;



if (!$id_usuario) {
    // Si no hay usuario logueado, no se puede consultar
    $error_consulta = "No se pudo identificar al usuario. Inicia sesión nuevamente.";
} else {
    try {
        $modelPublicacion = new Model_Publicacion();

        // Obtener publicaciones del usuario con estatus aprobada
        $publicaciones_usuario = $modelPublicacion->obtenerPublicacionesUsuarioAprobadas($id_usuario);

        if (empty($publicaciones_usuario)) {
            $error_consulta = "Aún no tienes publicaciones aprobadas.";
        }

        else {
             // 2. [LÓGICA MOVIDA Y CORREGIDA] Si hay publicaciones, contar los likes
             $modelInteraccion = new Model_Interaccion();
             $modelComentario = new Model_Comentario(); 
             foreach ($publicaciones_usuario as &$pub) {
                 $pub['likes_count'] = $modelInteraccion->contarLikes($pub['id']); 
                 $pub['comentarios_count'] = $modelComentario->contarComentarios($pub['id']);
             }
             unset($pub); // Siempre romper la referencia después de un foreach con &
        }

    } catch (Exception $e) {
        error_log("Error al cargar publicaciones del usuario: " . $e->getMessage());
        $error_consulta = "Error en el servidor al cargar las publicaciones. Inténtalo más tarde.";
    }
}

// Las variables se enviarán a la vista
