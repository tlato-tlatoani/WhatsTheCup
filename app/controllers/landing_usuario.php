<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Conexion.php';

$usuario = [];
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_usuario) {
    // Si no hay sesión, regresamos al login
    header("Location: /WhatsTheCup/index.php?route=iniciarsesion");
    exit();
}

try {
    $conexion = new Conexion();
    $conn = $conexion->getConnection();

    // Ejecutar el SP que tú creaste
    $stmt = $conn->prepare("CALL sp_consultar_usuarios(?)");
    $stmt->execute([$id_usuario]);

    // Obtener datos
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $usuario = [];
    }

    $stmt->closeCursor();

} catch (Exception $e) {
    $usuario = [];
}
