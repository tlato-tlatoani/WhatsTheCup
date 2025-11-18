<?php

require_once PROJECT_ROOT . '/app/models/aprobar_rechazar_publicacion.php';

$model = new Model_Publicacion();

$post_id = $_POST['post_id'] ?? null;
$accion  = $_POST['accion'] ?? null;

if (!$post_id || !$accion) {
    $_SESSION['mensaje_admin'] = "Datos incompletos.";
    header("Location: " . BASE_URL . "index.php?route=ad_aprobar_posts");
    exit();
}

// acción → decisión para SP
$decision = ($accion === "aprobar") ? 1 : -1;

// Ejecuta el SP
$result = $model->actualizarAprobacionPublicacion((int)$post_id, $decision);

// Mensaje para mostrar en la vista admin
$_SESSION['mensaje_admin'] = $result['message'] ?? "Operación realizada.";

// Redirige de vuelta
header("Location: " . BASE_URL . "index.php?route=ad_aprobar_posts");
exit();
