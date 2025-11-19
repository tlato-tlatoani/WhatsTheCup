<?php
// tlato-tlatoani/whatsthecup/WhatsTheCup-post/app/controllers/aprobar_rechazar_publicacion.php

// CORRECCIÓN CRÍTICA: Se corrige la ruta del archivo del modelo.
// Debe ser 'Model_Publicacion.php'
require_once PROJECT_ROOT . '/app/models/Model_Publicacion.php';

// Ahora la clase Model_Publicacion existe y se puede instanciar
$model = new Model_Publicacion();

$post_id = $_POST['post_id'] ?? null;
$accion  = $_POST['accion'] ?? null;

if (!$post_id || !$accion) {
    $_SESSION['mensaje_admin'] = "Datos incompletos o acceso inválido.";
    header("Location: " . BASE_URL . "index.php?route=ad_aprobar_posts");
    exit();
}

// acción → decisión para SP: 1 para aprobar, -1 para rechazar
// El valor de $accion viene del campo oculto en el formulario de la vista.
$decision = ($accion === "aprobar") ? 1 : -1;

// Ejecuta el SP
$result = $model->actualizarAprobacionPublicacion((int)$post_id, $decision);

// Mensaje para mostrar en la vista admin. 
// Se incluye el manejo de errores más específico.
if (!$result['success']) {
    $_SESSION['mensaje_admin'] = 'ERROR al procesar la publicación: ' . ($result['message'] ?? 'Error desconocido de la BD.');
} else {
    $_SESSION['mensaje_admin'] = $result['message'] ?? "Operación de " . $accion . " realizada correctamente.";
}

// Redirige de vuelta
header("Location: " . BASE_URL . "index.php?route=ad_aprobar_posts");
exit();

// El bloque extra de '}' en el código original se elimina.