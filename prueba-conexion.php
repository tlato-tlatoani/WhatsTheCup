<?php

// 1. Incluimos el archivo de la clase de la base de datos
require_once 'Conexion.php';

echo "<h1>Probando la conexión a la Base de Datos...</h1>";

try {
    // 2. Creamos una instancia de la clase Database
    $db = new Conexion();

    // 3. Intentamos obtener el objeto de conexión PDO
    $conexion = $db->getConnection();

    // 4. Verificamos si la conexión fue exitosa
    if ($conexion) {
        echo "<p style='color: green; font-size: 20px;'><strong>¡Conexión exitosa! ✅</strong></p>";
        echo "<p>¡Todo listo para empezar a construir las consultas!</p>";
    } else {
        // Este mensaje es redundante si el constructor falla, pero es una buena práctica
        echo "<p style='color: red; font-size: 20px;'><strong>Error: No se pudo establecer la conexión.</strong></p>";
    }

} catch (PDOException $e) {
    // 5. Si algo sale mal en el constructor, se captura la excepción
    echo "<p style='color: red; font-size: 20px;'><strong>¡Error de conexión! ❌</strong></p>";
    echo "<p><strong>Detalle del error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Por favor, revisa tus credenciales (host, dbname, user, password) en el archivo <code>app/models/Database.php</code>.</p>";
} catch (Exception $e) {
    echo "<p style='color: red; font-size: 20px;'><strong>Ha ocurrido un error inesperado:</strong></p>";
    echo "<p>" . $e->getMessage() . "</p>";
}

?>