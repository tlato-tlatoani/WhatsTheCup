<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// La ruta a la clase de validación original
require_once __DIR__ . '/api/Validar_Registro.php'; 
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'UserModel.php'; 


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Limpiar errores previos al iniciar el proceso
unset($_SESSION['errores_registro']);
unset($_SESSION['datos_registro']); 

if (isset($_POST['btn_registrar'])) {

    $erroresValidacion = [];
    $esValido = true;

    $contrasenna_raw = $_POST['CONTRASENNA'] ?? null;
    $fechaNacimiento = $_POST['NACIMIENTO'] ?? null;
    
    // Asignar variables limpias/transformadas que pasaremos al Model
    $datosRegistro = [];

    if ($contrasenna_raw === null || $contrasenna_raw === '') {
        $esValido = false;
        $erroresValidacion['contrasenna'] = 'La contraseña es requerida.';
    } else {

        $validacionPass = validarContrasenna($contrasenna_raw);
        if (!$validacionPass['valido']) {
            $esValido = false;
            $erroresValidacion['contrasenna'] = $validacionPass['mensaje'];
        }

        $datosRegistro['CONTRASENNA'] = $contrasenna_raw; 
    }

    if ($fechaNacimiento === null || $fechaNacimiento === '') {
        $esValido = false;
        $erroresValidacion['nacimiento'] = 'La fecha de nacimiento es requerida.';
    } else {
        $validacionEdad = validarEdad($fechaNacimiento, EDAD_MINIMA);
        if (!$validacionEdad['valido']) {
            $esValido = false;
            $erroresValidacion['nacimiento'] = $validacionEdad['mensaje'];
        }
        $datosRegistro['NACIMIENTO'] = $fechaNacimiento;
    }
   
    $datosRegistro['NOMBRES'] = htmlspecialchars($_POST['NOMBRES'] ?? '');
    $datosRegistro['APELLIDO_P'] = htmlspecialchars($_POST['APELLIDO_P'] ?? '');
    $datosRegistro['APELLIDO_M'] = htmlspecialchars($_POST['APELLIDO_M'] ?? '');
    
    $GENERO_INPUT = $_POST['GENERO'] ?? '';
    $datosRegistro['GENERO'] = '';
    if ($GENERO_INPUT === "Masculino") {
        $datosRegistro['GENERO'] = "M";
    } elseif ($GENERO_INPUT === "Femenino") {
        $datosRegistro['GENERO'] = "F";
    }
    
    $datosRegistro['NACIONALIDAD'] = htmlspecialchars($_POST['NACIONALIDAD'] ?? '');
    $datosRegistro['PAIS_ORIGEN'] = htmlspecialchars($_POST['PAIS_ORIGEN'] ?? '');

    $userModel = new UserModel();
    $correo_limpio = filter_var($_POST['CORREO'] ?? '', FILTER_SANITIZE_EMAIL);
    $datosRegistro['CORREO'] = $correo_limpio;

    // --- NUEVA VALIDACIÓN DE CORREO EXISTENTE ---
    if (empty($correo_limpio) || !filter_var($correo_limpio, FILTER_VALIDATE_EMAIL)) {
        $esValido = false;
        $erroresValidacion['correo'] = 'El correo electrónico no es válido.';
    } else {
        $validacionCorreo = $userModel->consultarCorreoExistente($correo_limpio);

        if (is_array($validacionCorreo)) {
        // Error grave de BD
        $esValido = false;
        $erroresValidacion['general'] = 'Error al verificar la existencia del correo en la base de datos.';

        } elseif ($validacionCorreo === true) {
         // Correo ya existe
         $esValido = false;
        $erroresValidacion['correo'] = 'Este correo electrónico ya está registrado.';
        }

    }

    $imagen_perfil_blob = NULL;
    if (isset($_FILES['IMAGEN_PERFIL']) && $_FILES['IMAGEN_PERFIL']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['IMAGEN_PERFIL']['type'], $allowedTypes)) {
             $imagen_perfil_blob = file_get_contents($_FILES['IMAGEN_PERFIL']['tmp_name']);
        } else {
             $esValido = false;
             $erroresValidacion['imagen'] = 'Tipo de imagen no permitido.';
        }
    } elseif (isset($_FILES['IMAGEN_PERFIL']) && $_FILES['IMAGEN_PERFIL']['error'] !== UPLOAD_ERR_NO_FILE) {
        $esValido = false;
        $erroresValidacion['imagen'] = 'Error al subir la imagen de perfil. Código: ' . $_FILES['IMAGEN_PERFIL']['error'];
    }
    $datosRegistro['IMAGEN_PERFIL_BLOB'] = $imagen_perfil_blob;


    // --- 3. REDIRECCIÓN SI FALLA LA VALIDACIÓN ---
    if (!$esValido) {
        $_SESSION['errores_registro'] = $erroresValidacion;
        $_SESSION['datos_registro'] = $_POST;
        unset($_SESSION['datos_registro']['CONTRASENNA']);
        if (!defined('BASE_URL')) {
            define('BASE_URL', '/WhatsTheCup/');
        }
        header("Location: " . BASE_URL . "index.php?route=registro");
        exit;
    }


    
    //$userModel = new UserModel(); 

   $resultadoRegistro = $userModel->registrarUsuario($datosRegistro);


    if ($resultadoRegistro === true) {

        unset($_SESSION['datos_registro']); 
        if (!defined('BASE_URL')) {
            define('BASE_URL', '/WhatsTheCup/');
        }
        header("Location: " . BASE_URL . "index.php?route=iniciarsesion&registro=exitoso");
        exit;

    } else {
        // Error de BD (capturado y retornado por el Model)
        $errorInfo = $resultadoRegistro['errorInfo'];
        $errorMessage = 'Error DB al registrar: ' . ($errorInfo[2] ?? 'Error desconocido.');

        // Puedes inspeccionar el código de error de SQL ($errorInfo[1]) para dar mensajes específicos
        // Por ejemplo, si el SP detectó que el correo ya existe.
        if (isset($errorInfo[1]) && $errorInfo[1] == 2627) { 
             $errorMessage = 'El correo electrónico o nombre de usuario ya está registrado.';
        }
        
        error_log('Error en registro_usuario.php (Controller): ' . $errorMessage);
        $_SESSION['errores_registro'] = ['general' => $errorMessage];
        $_SESSION['datos_registro'] = $_POST;
        unset($_SESSION['datos_registro']['CONTRASENNA']);

        if (!defined('BASE_URL')) {
            define('BASE_URL', '/WhatsTheCup/');
        }

        header("Location: " . BASE_URL . "index.php?route=registro");
        exit;
    }

} else {
 
    if (!defined('BASE_URL')) {
        define('BASE_URL', '/WhatsTheCup/');
    }
    header("Location: " . BASE_URL . "index.php?route=registro");
    exit;
}
?>