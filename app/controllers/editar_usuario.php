<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__, 2));
}

require_once PROJECT_ROOT . '/app/models/UserModel.php';

// Validar login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: " . BASE_URL . "index.php?route=iniciarsesion");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Modelo
$model = new UserModel();
$usuario = $model->consultarUsuarioPorId($id_usuario);

// Vista
require PROJECT_ROOT . '/app/views/user-views/Us-editar_usuario.php';
