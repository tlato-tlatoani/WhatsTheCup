<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_clean();
// 1. Cabeceras para manejar JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

function devolver_error($message, $http_code = 500) {
    http_response_code($http_code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

require_once dirname(__DIR__) . '/models/Model_Mundial.php';
require_once dirname(__DIR__) . '/models/Model_Categorias.php';

// 2. Obtener el ID del Mundial (Asumiendo que se pasa por GET)
$id_mundial = $_GET['id'] ?? null;

if (!$id_mundial) {
    echo json_encode(['success' => false, 'message' => 'ID de mundial no proporcionado.']);
    exit;
}

try {
   $modelMundial = new Model_Mundial();
   $modelCategorias = new Model_Categorias(); // Instancia el modelo de categorías

    // 4. Obtener datos del Modelo
    $mundial = $modelMundial->obtenerMundialPorId($id_mundial);
    $categorias = $modelCategorias->obtenerCategorias(); // Obtén las categorías

    if ($mundial) {
        // 5. Devolver los datos combinados en formato JSON
        echo json_encode([
            'success' => true,
            'nombre_mundial' => $mundial['titulo'] ?? 'Mundial Desconocido',
            'categorias' => $categorias // Ahora $categorias está definida
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mundial no encontrado.']);
    }

} catch (Exception $e) {
    // Manejo de errores de base de datos o conexión
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>