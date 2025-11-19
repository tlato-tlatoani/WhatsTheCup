<?php
// /app/controllers/buscar_publicaciones.php

require_once PROJECT_ROOT . '/app/models/Model_Publicacion.php';

// Recopilar todos los posibles filtros de $_GET
$filtros = [
    'q'             => filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS),
    'desde'         => filter_input(INPUT_GET, 'desde', FILTER_SANITIZE_SPECIAL_CHARS),
    'hasta'         => filter_input(INPUT_GET, 'hasta', FILTER_SANITIZE_SPECIAL_CHARS),
    'categoria_id'  => filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT), // Usaremos 'categoria' en el form
    'usuario_id'    => filter_input(INPUT_GET, 'usuario_id', FILTER_VALIDATE_INT) // Si usas un input oculto para el ID de usuario
];

$resultados_busqueda = [];
$error_busqueda = null;

// Chequeo mínimo: si no hay término de búsqueda ni filtros
if (empty(array_filter($filtros))) {
    $error_busqueda = "Introduce un término o selecciona un filtro para iniciar la búsqueda.";
} else {
    try {
        $model = new Model_Publicacion();
        $resultados_busqueda = $model->buscarPublicaciones($filtros);

        if (empty($resultados_busqueda)) {
            $error_busqueda = "No se encontraron publicaciones que coincidan con los criterios.";
        }

    } catch (Exception $e) {
        error_log("Error en la búsqueda: " . $e->getMessage());
        $error_busqueda = "Error en el servidor al realizar la búsqueda.";
    }
}

// $filtros, $resultados_busqueda y $error_busqueda disponibles para Us-Busqueda.php