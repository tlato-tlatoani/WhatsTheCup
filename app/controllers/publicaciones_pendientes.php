<?php

// 1. Incluir el Modelo de Publicación usando la ruta absoluta PROJECT_ROOT.
// Esto garantiza que la inclusión funciona sin importar dónde se ejecute el script.
// Ruta Absoluta: C:\xampp\htdocs\WhatsTheCup/app/models/Model_Publicacion.php
require_once PROJECT_ROOT . '/app/models/Model_Publicacion.php'; 

// 2. Definir una variable para almacenar los datos (se usará en la vista)
$publicaciones_pendientes = [];
$error_consulta = null;
    error_log("⚡ Controlador publicaciones_pendientes cargado");

    
try {
    // 3. Instanciar el Modelo
    $modelPublicacion = new Model_Publicacion();

    // 4. Obtener las publicaciones pendientes (usando la vista)
    $publicaciones_pendientes = $modelPublicacion->obtenerPublicacionesPendientes();

    // Opcional: Si no hay resultados, podemos establecer un mensaje
    if (empty($publicaciones_pendientes)) {
        $error_consulta = "No hay publicaciones pendientes de aprobación.";
    }

} catch (Exception $e) {
    // 5. Manejo de errores de servidor/lógica
    error_log("Error al cargar publicaciones pendientes: " . $e->getMessage());
    $error_consulta = "Error en el servidor al cargar los datos. [Detalle: " . $e->getMessage() . "]";
    // Hemos mejorado el mensaje de error para debug
}

// Ahora, el array $publicaciones_pendientes (y $error_consulta)
// estará disponible para la vista Ad-AprobarPosts.php cuando se incluya.

?>