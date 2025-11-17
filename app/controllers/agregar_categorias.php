<?php

if (!defined('BASE_URL')) {
    define('BASE_URL', '/WhatsTheCup/');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: " . BASE_URL . "index.php?route=iniciarsesion");
    exit;
}

require_once dirname(__DIR__, 2) . '/app/models/Model_Categorias.php';

$model = new Model_Categorias();

$nombre = trim($_POST['nombre_categoria'] ?? '');

if ($nombre === '') {
    echo "Nombre vacío";
    exit;
}

$id = $model->agregarCategoria($nombre, $_SESSION['id_usuario']);

if ($id) {
    header("Location: " . BASE_URL . "index.php?route=adcategorias&ok=1");
} else {
    header("Location: " . BASE_URL . "index.php?route=adcategorias&error=1");
}

exit;
