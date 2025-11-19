<?php
require_once PROJECT_ROOT . '/app/models/Model_Interaccion.php';
session_start();

$usuarioId = $_SESSION['id_usuario'] ?? null;
$input = json_decode(file_get_contents('php://input'), true);
$publicacionId = $input['publicacion_id'] ?? null;

header('Content-Type: application/json');

if (!$usuarioId || !$publicacionId) {
    echo json_encode(['success' => false, 'message' => 'Usuario o publicación inválidos']);
    exit;
}

$model = new Model_Interaccion();
$liked = $model->toggleLike($usuarioId, $publicacionId);
$likes = $model->contarLikes($publicacionId);

echo json_encode([
    'success' => true,
    'liked' => $liked,
    'likes' => $likes
]);

