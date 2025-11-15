<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__, 2) . '/app/models/UserModel.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/WhatsTheCup/');
}

# 1. Validar acceso por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['btn_actualizar'])) {
    header("Location: " . BASE_URL . "index.php?route=perfil");
    exit;
}

# 2. Validar sesión
$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    header("Location: " . BASE_URL . "index.php?route=iniciarsesion");
    exit;
}

# 3. Recibir datos del formulario
$nombres        = trim($_POST['NOMBRES'] ?? '');
$apellido_p     = trim($_POST['APELLIDO_P'] ?? '');
$apellido_m     = trim($_POST['APELLIDO_M'] ?? '');
$correo         = trim($_POST['CORREO'] ?? '');
$contrasenna    = trim($_POST['CONTRASENNA'] ?? '');
$nacimiento     = trim($_POST['NACIMIENTO'] ?? '');
$nacionalidad   = trim($_POST['NACIONALIDAD'] ?? '');
$genero_input   = trim($_POST['GENERO'] ?? '');
$pais_origen    = trim($_POST['PAIS_ORIGEN'] ?? '');

# 4. Validar obligatorios
if (empty($nombres) || empty($apellido_p) || empty($correo)) {
    echo "Error: Los campos Nombres, Apellido Paterno y Correo son obligatorios.";
    exit;
}

# 5. Convertir género a M/F
$genero = null;
if ($genero_input === "Masculino") {
    $genero = "M";
} elseif ($genero_input === "Femenino") {
    $genero = "F";
}

# 6. Procesar imagen si se sube una
$imagen_perfil = null;
if (!empty($_FILES['IMAGEN_PERFIL']['tmp_name'])) {
    $imagen_perfil = file_get_contents($_FILES['IMAGEN_PERFIL']['tmp_name']);
}

# 7. Construir arreglo final de datos
$datos = [
    'nombres'       => $nombres,
    'apellido_p'    => $apellido_p,
    'apellido_m'    => $apellido_m,
    'correo'        => $correo,
    'nacimiento'    => $nacimiento,
    'genero'        => $genero,
    'nacionalidad'  => $nacionalidad,
    'pais_origen'   => $pais_origen,
    'contrasenna'   => null,
    'imagen'        => $imagen_perfil
];

# 8. Solo actualizar contraseña si escriben algo
if (!empty($contrasenna)) {
    $datos['contrasenna'] = $contrasenna;
}

# 9. Ejecutar actualización
$userModel = new UserModel();
$userModel->actualizarUsuario($id_usuario, $datos);

# 10. Regresar al perfil
header("Location: " . BASE_URL . "index.php?route=perfil");
exit;
