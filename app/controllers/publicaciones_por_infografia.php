<?php
// tlato-tlatoani/whatsthecup/WhatsTheCup-post/app/controllers/publicaciones_por_infografia.php

require_once PROJECT_ROOT . '/app/models/Model_Publicacion.php'; 
require_once PROJECT_ROOT . '/app/models/Model_Mundial.php'; // Se asume que este modelo existe para obtener la info de la Infografía

// 1. Obtener el ID de la Infografía (Mundial) desde la URL
// Asumimos que la URL pasa el ID como parámetro GET, e.g., index.php?route=us_infografia&id=5
$id_infografia = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Variable que contendrá las publicaciones para la vista
$publicaciones_infografia = [];
$infografia_data = null; 
$error_consulta = null;

if (!$id_infografia) {
    // Si no hay ID válido, preparamos un mensaje de error para la vista.
    $error_consulta = "ID de Infografía no proporcionado o inválido.";
} else {
    try {
        // 2. Obtener la información de la Infografía/Mundial (Para usar el nombre en el título)
        $modelMundial = new Model_Mundial();
        // Asumiendo que Model_Mundial tiene un método para obtener el mundial por su ID.
        $infografia_data = $modelMundial->obtenerMundialPorId($id_infografia); 

        // 3. Obtener las publicaciones aprobadas
        $modelPublicacion = new Model_Publicacion();
        $publicaciones_infografia = $modelPublicacion->obtenerPublicacionesPorMundialAprobadas($id_infografia);

        // Mensaje si no hay publicaciones o si la infografía no existe
        if (!$infografia_data) {
             $error_consulta = "La Infografía solicitada no existe.";
             $publicaciones_infografia = []; // Asegurar que el array esté vacío en caso de error.
        } elseif (empty($publicaciones_infografia)) {
             $error_consulta = "No hay publicaciones aprobadas para esta Infografía.";
        }

    } catch (Exception $e) {
        error_log("Error al cargar publicaciones por Infografía: " . $e->getMessage());
        $error_consulta = "Error en el servidor al cargar los datos. Inténtelo más tarde.";
    }
}

// Las variables se pasan a la vista que incluya este archivo.