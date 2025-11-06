<?php
// /app/controllers/actualizar_usuario.php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Conexion.php';
require_once __DIR__ . '/api/Validar_Registro.php';

session_start();

$conn = (new Conexion())->getConnection();

// Verificar sesión
$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    header("Location: /WhatsTheCup/index.php");
    exit;
}

// Inicializar errores y mensajes
$errores = $_SESSION['errores_actualizacion'] ?? [];
$mensaje_exito = $_SESSION['mensaje_exito'] ?? '';
$datos = $_SESSION['datos_actualizacion'] ?? [];
unset($_SESSION['errores_actualizacion'], $_SESSION['mensaje_exito'], $_SESSION['datos_actualizacion']);

// Obtener datos del usuario
try {
    $stmt = $conn->prepare("CALL sp_consultar_usuarios(?)");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    echo($usuario["NOMBRES"]);
    if (!$usuario) $usuario = [];
} catch (PDOException $e) {
    $usuario = [];
    $errores['general'] = "No se pudieron cargar los datos del usuario: " . $e->getMessage();
}

// Si hay datos previos de un intento de actualización fallido, sobreescribir
if (!empty($datos)) {
    $usuario = array_merge($usuario, $datos);
}

// Procesar formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_actualizar'])) {

    // Recoger datos del formulario
    $nombres      = trim($_POST['NOMBRES'] ?? '');
    $apellido_p   = trim($_POST['APELLIDO_P'] ?? '');
    $apellido_m   = trim($_POST['APELLIDO_M'] ?? '');
    $correo       = trim($_POST['CORREO'] ?? '');
    $contrasenna  = $_POST['CONTRASENNA'] ?? '';
    $nacimiento   = $_POST['NACIMIENTO'] ?? '';
    $genero       = $_POST['GENERO'] ?? '';
    $nacionalidad = trim($_POST['NACIONALIDAD'] ?? '');
    $pais_origen  = trim($_POST['PAIS_ORIGEN'] ?? '');

    // Validaciones
    if (empty($nombres) || empty($apellido_p) || empty($apellido_m) || empty($nacionalidad) || empty($pais_origen)) {
        $errores['campos'] = "Todos los campos son obligatorios.";
    }

    // Validar contraseña (si se ingresó)
    if (!empty($contrasenna)) {
        $validarContrasenna = validarContrasenna($contrasenna);
        if (!$validarContrasenna['valido']) {
            $errores['contrasenna'] = $validarContrasenna['mensaje'];
        }
    } else {
        // Si no ingresó nueva contraseña, mantener la anterior
        $contrasenna = $usuario['CONTRASENNA'] ?? '';
    }

    // Validar fecha de nacimiento
    if (!empty($nacimiento)) {
        $validarEdad = validarEdad($nacimiento, EDAD_MINIMA);
        if (!$validarEdad['valido']) {
            $errores['nacimiento'] = $validarEdad['mensaje'];
        }
    }

    // Si hay errores, guardarlos en sesión y redirigir
    if (!empty($errores)) {
        $_SESSION['errores_actualizacion'] = $errores;
        $_SESSION['datos_actualizacion'] = $_POST;
        header("Location: /WhatsTheCup/app/views/user-views/Us-Perfil.php");
        exit;
    }

    // Procesar imagen de perfil si se subió
    $imagen_perfil = null;
    if (!empty($_FILES['IMAGEN_PERFIL']['tmp_name'])) {
        $imagen_perfil = file_get_contents($_FILES['IMAGEN_PERFIL']['tmp_name']);
    }

    // Actualizar usuario con SP
    try {
        $stmt = $conn->prepare("CALL sp_actualizar_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $id_usuario,
            $nombres,
            $apellido_p,
            $apellido_m,
            $nacimiento,
            $genero,
            $nacionalidad,
            $pais_origen,
            $correo,
            $contrasenna,
            $imagen_perfil
        ]);

        $_SESSION['mensaje_exito'] = "Perfil actualizado correctamente.";
        header("Location: /WhatsTheCup/app/views/user-views/Us-Perfil.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['errores_actualizacion'] = ['general' => "Error al actualizar: " . $e->getMessage()];
        $_SESSION['datos_actualizacion'] = $_POST;
        header("Location: /WhatsTheCup/app/views/user-views/Us-Perfil.php");
        exit;
    }
}
?>
