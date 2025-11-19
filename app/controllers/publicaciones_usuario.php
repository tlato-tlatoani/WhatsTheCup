<?php
// tlato-tlatoani/whatsthecup/WhatsTheCup-post/app/controllers/publicaciones_por_usuario.php

require_once PROJECT_ROOT . '/app/models/Model_Publicacion.php';

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

    } catch (Exception $e) {
        error_log("Error al cargar publicaciones del usuario: " . $e->getMessage());
        $error_consulta = "Error en el servidor al cargar las publicaciones. Inténtalo más tarde.";
    }
}

// Las variables se enviarán a la vista
