<?php

// Requerir el modelo (Ajusta la ruta si es necesario)
require_once dirname(__DIR__) . '/models/Model_Publicacion.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Función de utilidad para manejar la respuesta
function enviarRespuestaJSON($success, $message, $data = [], $http_code = 200) {
    // Limpieza de buffer y encabezados JSON
    if (ob_get_length()) {
        ob_clean(); 
    }
    header('Content-Type: application/json');
    http_response_code($http_code);
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
    exit;
}

// 1. Verificar el método y el campo de envío (Asumiendo que es un form submit)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['i_archivo'])) {
    // Si no se usa un form submit con el nombre de archivo esperado o el método es incorrecto
    enviarRespuestaJSON(false, 'Acceso no permitido o datos de archivo incompletos.', [], 405);
}

// 2. Recolección y saneamiento de datos
$titulo      = trim($_POST['titulo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
// Convertir IDs a enteros. Es vital sanitizar, pero aquí solo se fuerza el tipo.

$autorId = $_SESSION['id_usuario'] ?? null; // Intentamos obtener el ID de la sesión

// 3. Validación de datos obligatorios (Actualizada)
if (empty($autorId) || !is_numeric($autorId) || $autorId <= 0) {
     // Si no hay sesión o el ID es inválido, terminamos la ejecución
     enviarRespuestaJSON(false, 'El ID del autor es inválido o falta. Inicia sesión para publicar.', [], 403);
}

// Convertimos a entero para asegurar el tipo de dato antes de pasarlo al modelo
$autorId = (int)$autorId;


$mundialId   = filter_var($_POST['mundial_id'] ?? null, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
$categoriaId = filter_var($_POST['categoria_id'] ?? 0, FILTER_VALIDATE_INT);
$fileData    = $_FILES['i_archivo']; // Asumiendo que el campo de subida es 'i_archivo'

// 3. Validación de datos obligatorios
if ($autorId === false || $autorId <= 0) {
     enviarRespuestaJSON(false, 'El ID del autor es inválido o falta.', [], 400);
}
if ($categoriaId === false || $categoriaId <= 0) {
     enviarRespuestaJSON(false, 'La categoría de la publicación es obligatoria.', [], 400);
}
if (empty($titulo) || empty($descripcion)) {
    enviarRespuestaJSON(false, 'El título y la descripción no pueden estar vacíos.', [], 400);
}


// 4. Instancia del Modelo y Ejecución
$model = new Model_Publicacion();
$resultado = $model->registrarPublicacion(
    $titulo, 
    $descripcion, 
    $fileData, 
    $autorId, 
    $mundialId, 
    $categoriaId
);

// 5. Devolver Respuesta
if ($resultado['success']) {
    // 201 Created para una creación exitosa
    enviarRespuestaJSON(true, $resultado['message'], ['publicacion_id' => $resultado['publicacion_id']], 201);
} else {
    // 500 Internal Server Error o 400 Bad Request si el mensaje indica un problema de datos
    $http_code = strpos($resultado['message'], 'Base de Datos') !== false ? 500 : 400;
    enviarRespuestaJSON(false, $resultado['message'], [], $http_code);
}