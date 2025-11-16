<?php

if (!defined('BASE_URL')) {
    define('BASE_URL', '/WhatsTheCup/');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'ADMIN') {
    header("Location: " . BASE_URL . "index.php?route=adlanding");
    exit;
}
require_once dirname(__DIR__, 2) . '/app/models/Model_Mundial.php';

$model = new Model_Mundial();

try {

    // 1. RECIBIR FORMULARIO
    $titulo      = trim($_POST['i_titulo'] ?? '');
    $pais        = trim($_POST['i_pais'] ?? '');
    $anio        = trim($_POST['i_anio'] ?? '');
    $descripcion = trim($_POST['i_descripcion'] ?? '');

    $campeon     = trim($_POST['i_campeon'] ?? '');
    $subcampeon  = trim($_POST['i_subcampeon'] ?? '');
    $marcador    = trim($_POST['i_marcador'] ?? '');
    $goleador    = trim($_POST['i_goleador'] ?? '');
    $cantante    = trim($_POST['i_cantante'] ?? '');

    // Equipos JSON → array → string
    $equiposArray = json_decode($_POST['equipos_json'] ?? '[]', true);
    $equiposTexto = implode(",", $equiposArray);

    // JSON detalles
    $detallesJSON = json_encode([
        'campeon'     => $campeon,
        'subcampeon'  => $subcampeon,
        'marcador'    => $marcador,
        'goleador'    => $goleador,
        'cantante'    => $cantante
    ], JSON_UNESCAPED_UNICODE);

    // JSON sede
    $sedesJSON = json_encode([$pais], JSON_UNESCAPED_UNICODE);


    // 2. ARMAR DATA PARA EL MODELO
    $data = [
        'titulo'        => $titulo,
        'pais'          => $pais,
        'anio'          => $anio,
        'descripcion'   => $descripcion,
        'equiposTexto'  => $equiposTexto,
        'detallesJSON'  => $detallesJSON,
        'sedesJSON'     => $sedesJSON,
        'admin_id'      => $_SESSION['id_usuario'],

    ];

    // 3. LLAMAR AL MODELO
    $idInsertado = $model->registrarMundial($data, $_FILES);

    if ($idInsertado) {
        header("Location: " . BASE_URL . "index.php?route=adlanding&ok=1");
        exit;
    } else {
        echo "Error: No se pudo insertar el mundial.";
    }

} catch (Exception $e) {
    echo "Error en controlador: " . $e->getMessage();
}
