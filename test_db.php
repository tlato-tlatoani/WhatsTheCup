<?php
$conexion = new mysqli("127.0.0.1", "root", "", "bdm_proyecto", 3307);

if ($conexion->connect_error) {
    die("❌ Error de conexión: " . $conexion->connect_error);
}

echo "✅ Conectado correctamente a la base de datos";
?>
