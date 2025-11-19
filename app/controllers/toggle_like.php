<?php
require_once '../models/Model_Publicacion.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["error" => "Debe iniciar sesión"]);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_publicacion = $_POST['id_publicacion'] ?? null;

if (!$id_publicacion) {
    echo json_encode(["error" => "Publicación no válida"]);
    exit;
}

$model = new Model_Publicacion();
$status = $model->toggleLike($id_usuario, $id_publicacion);
$likes = $model->contarLikes($id_publicacion);

echo json_encode([
    "status" => $status,
    "likes" => $likes
]);
