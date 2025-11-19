<?php
// /app/controllers/agregar_comentario.php

// Asegúrate de que las rutas relativas sean correctas
require_once dirname(__DIR__, 2) . '/app/models/Model_Comentario.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


header('Content-Type: application/json');

// ==========================================================
// 1. OBTENER Y VALIDAR DATOS (SÓLO LECTURA JSON)
// ==========================================================

// Leer y decodificar el cuerpo JSON (la forma correcta para tu AJAX)
$json_data = file_get_contents('php://input'); // [CORREGIDO] Definimos $json_data aquí
$input = json_decode($json_data, true);


$usuarioId = $_SESSION['id_usuario'] ?? null;
$publicacionId = $input['publicacion_id'] ?? null;
$contenido = $input['contenido'] ?? null;

if ($publicacionId !== null) {
    $publicacionId = (int)$publicacionId;
}

// ==========================================================
// [INICIO DEBUGGING TEMPORAL Y CORREGIDO]
// ==========================================================

error_log("-----------------------------------------");
error_log("COMENTARIO DEBUG: Rutina de Validación");
error_log("1. JSON Body Received: " . $json_data);
error_log("2. Decoded Input Array: " . print_r($input, true));
error_log("3. Session User ID: " . $usuarioId);
error_log("4. Publicacion ID (PID): " . $publicacionId);
error_log("5. Contenido: " . $contenido);
error_log("-----------------------------------------");

// ==========================================================
// [FIN DEBUGGING TEMPORAL Y CORREGIDO]
// ==========================================================

// Validación de usuario
if (!$usuarioId) {

    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión para comentar.']);
    exit;
}

// Validación de datos: Usamos (int)$publicacionId para asegurarnos de que el valor 
// sea tratado como un número entero para la base de datos y la validación
if (!$publicacionId || empty(trim($contenido))) { 
    error_log("FALLA DE VALIDACION FINAL: PID=" . print_r($publicacionId, true) . ", Content=" . print_r($contenido, true));

echo json_encode(['success' => false, 'message' => 'Datos de publicación o contenido inválidos.']);
exit;
}

// Limpiar el contenido (prevención básica XSS)
$contenido = trim(htmlspecialchars($contenido));

// ==========================================================
// 2. PROCESAR Y GUARDAR
// ==========================================================


try {
    $modelComentario = new Model_Comentario();
    
    // 1. Guardar el comentario y obtener el ID
    $nuevoComentarioId = $modelComentario->agregarComentario((int)$publicacionId, (int)$usuarioId, $contenido);

    if ($nuevoComentarioId) {
        // 2. [CLAVE] Obtener todos los detalles del comentario recién creado
        $detalleComentario = $modelComentario->obtenerComentarioPorId($nuevoComentarioId);
        
        // 3. Obtener el conteo actualizado de comentarios
        $conteoComentarios = $modelComentario->contarComentarios((int)$publicacionId);

        // 4. Devolver los detalles y el conteo en el JSON
        echo json_encode([
            'success' => true, 
            'message' => 'Comentario agregado con éxito.',
            'comentario' => $detalleComentario,      // Objeto con todos los datos
            'conteo' => $conteoComentarios            // Conteo actualizado
        ]);

    } else {
        error_log("Fallo SQL: El modelo devolvió FALSE. Revisar logs de MySQL.");   
        echo json_encode(['success' => false, 'message' => 'Error al guardar el comentario en la base de datos.']);
    }

} catch (Exception $e) {
    error_log("Error de excepción: " . $e->getMessage());    
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor.']); 
}
exit;
?>