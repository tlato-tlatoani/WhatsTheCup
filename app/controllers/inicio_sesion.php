<?php
// app/controllers/inicio_sesion.php

if (!defined('PROJECT_ROOT')) {
 http_response_code(500); exit("Error de configuración: Dependencias no cargadas.");
}


require_once PROJECT_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'UserModel.php';

if (session_status() == PHP_SESSION_NONE) {
 session_start();
}

if (isset($_POST['btn_iniciar_sesion'])) {
 
 // 1. OBTENCIÓN Y VALIDACIÓN DE DATOS
 $CORREO = filter_var(trim($_POST['CORREO'] ?? ''), FILTER_SANITIZE_EMAIL);
 $CONTRASENNA = $_POST['CONTRASENNA'] ?? '';
 
 $errores = [];

 if (!filter_var($CORREO, FILTER_VALIDATE_EMAIL)) {
 $errores['correo'] = "Correo no válido.";
 }
 if (empty($CONTRASENNA)) {
 $errores['contrasenna'] = "Debe ingresar una contraseña.";
 }

 // Manejo de errores de validación
 if (!empty($errores)) {
 $_SESSION['errores_login'] = $errores;
 $_SESSION['datos_login'] = ['CORREO' => $CORREO];
 header("Location: " . BASE_URL . "index.php?route=iniciarsesion&status=validation_error");
 exit();
 }

    $userModel = new UserModel();
 $usuario = $userModel->iniciarSesion($CORREO, $CONTRASENNA);

 
    // Caso 1: Error interno de base de datos
    if (isset($usuario['db_error'])) {
        error_log("Error fatal de BD en login: " . $usuario['db_error']); // Loguea el error interno
        $_SESSION['errores_login'] = ['general' => "Error interno del sistema. Por favor, intente más tarde."];
        $_SESSION['datos_login'] = ['CORREO' => $CORREO];
        header("Location: " . BASE_URL . "index.php?route=iniciarsesion&status=db_error");
        exit();

    } 
    // Caso 2: Éxito en el inicio de sesión
    else if ($usuario) {
 // Asignamos los datos del usuario a la sesión
        unset($_SESSION['errores_login']);
        unset($_SESSION['datos_login']);
        
 $_SESSION['id_usuario'] = $usuario['ID_USUARIO'] ?? null;
 $_SESSION['nombres'] = $usuario['NOMBRES'] ?? 'Usuario';
 $_SESSION['tipo_usuario'] = $usuario['TIPO_USUARIO'] ?? 'USER'; // Default a 'USER' si no se especifica
 
 
 // Redirección basada en el tipo de usuario
 if ($_SESSION['tipo_usuario'] === 'ADMIN') {
 header("Location: " . BASE_URL . "index.php?route=adlanding");
 } else {
 header("Location: " . BASE_URL . "index.php?route=landing");
 }
 exit();

 } 
    // Caso 3: Credenciales Inválidas (Modelo devolvió NULL)
    else {
 $_SESSION['errores_login'] = ['general' => "Correo o contraseña incorrectos."];
 $_SESSION['datos_login'] = ['CORREO' => $CORREO];
 header("Location: " . BASE_URL . "index.php?route=iniciarsesion&status=invalid_credentials");
 exit();
 }
}
?>