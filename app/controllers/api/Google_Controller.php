<?php
// app/controllers/api/GoogleController.php
// Este script maneja el callback de Google, autentica y registra al usuario.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// =================================================================
// 0. DEPENDENCIAS y CONFIGURACIÓN
// =================================================================

// Cargar la librería de Google y el modelo de usuario.
require_once PROJECT_ROOT . '/vendor/autoload.php';
require_once PROJECT_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'UserModel.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/WhatsTheCup/'); // Definición usada por index.php
}
// Aseguramos que la URL para el header Location sea absoluta
define('ABSOLUTE_BASE_URL', 'http://localhost' . BASE_URL);

// Reemplaza con tus CREDENCIALES
define('GOOGLE_CLIENT_ID', '442951345014-535grmggqi08tpjgvts530p408j9f0rb.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
// La URI DEBE coincidir con la registrada en Google Cloud
define('GOOGLE_REDIRECT_URI', 'http://localhost/WhatsTheCup/index.php?route=google-callback');


$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);


if (isset($_GET['code'])) {
    
    // --- LÍNEA DE DEBUG 1: Agrega esto ---
    //die("CHECKPOINT 1: Código recibido. Valor del código: " . $_GET['code']);
    // ------------------------------------

    // 2. Intercambiar el código por el token
    //$client->authenticate($_GET['code']);



    // =============================================================
    // 1. OBTENER DATOS DEL USUARIO DE GOOGLE
    // =============================================================
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);
    } catch (\Exception $e) {
        $_SESSION['error'] = 'Fallo en la comunicación con Google.';
     header('Location: ' . ABSOLUTE_BASE_URL . 'index.php?route=registro');
        exit;
    }

    $oauth2 = new Google_Service_Oauth2($client);
    $user_info = $oauth2->userinfo->get();

    $google_id = $user_info->id;
    $email = $user_info->email;
    $nombres = $user_info->givenName ?? 'Usuario';
    $apellido_p = $user_info->familyName ?? 'Google';
    $apellido_m = ''; // No disponible por defecto

    $userModel = new UserModel();

    // =============================================================
    // 2. BUSCAR USUARIO E INICIAR SESIÓN/REGISTRO
    // =============================================================
    
    // ASUMIMOS: Que la función buscarUsuarioOAuth y asociarGoogleId existen en UserModel
    $usuario = $userModel->buscarUsuarioOAuth($google_id, $email);

    if ($usuario) {
        // CASO A: USUARIO ENCONTRADO (Login)
        
        if (empty($usuario['GOOGLE_ID'])) {
            // El usuario existía por email/contraseña. Asociamos el Google ID.
            $userModel->asociarGoogleId($usuario['ID_USUARIO'], $google_id);
        }
        
        // ******* LÓGICA DE SESIÓN DUPLICADA *******
        unset($_SESSION['errores_login']);
        unset($_SESSION['datos_login']);
        unset($_SESSION['errores_registro']); // Limpia por si acaso
        unset($_SESSION['datos_registro']); // Limpia por si acaso
            
        $_SESSION['id_usuario'] = $usuario['ID_USUARIO'] ?? null;
        $_SESSION['nombres'] = $usuario['NOMBRES'] ?? 'Usuario';
        $_SESSION['tipo_usuario'] = $usuario['TIPO_USUARIO'] ?? 'USUARIO';
        
        // Redirección basada en el tipo de usuario
        if ($_SESSION['tipo_usuario'] === 'ADMIN') {
          header('Location: ' . ABSOLUTE_BASE_URL . 'index.php?route=adlanding');
        } else {
           header('Location: ' . ABSOLUTE_BASE_URL . 'index.php?route=landing');
        }
        exit();

    } else {
        // CASO B: USUARIO COMPLETAMENTE NUEVO (Registro)

        // Generamos contraseña dummy
        $contrasenna_dummy = hash('sha256', uniqid(rand(), true));

        $datos_registro = [
            'NOMBRES'       => $nombres,
            'APELLIDO_P'    => $apellido_p,
            'APELLIDO_M'    => $apellido_m,
            'NACIMIENTO'    => '1900-01-01',
            'GENERO'        => 'M',
            'NACIONALIDAD'  => '-',
            'PAIS_ORIGEN'   => '-',
            'CORREO'        => $email,
            'CONTRASENNA'   => $contrasenna_dummy,
            'IMAGEN_PERFIL_BLOB' => null
        ];

        // Aquí tu SP retorna el ID nuevo
        $nuevo_id = $userModel->registrarUsuario($datos_registro);

        // Si registrarUsuario devuelve un array, es un error
        if (is_array($nuevo_id)) {
            echo "<h1>Error al registrar usuario con Google</h1>";
            echo "<pre>";
            var_dump($nuevo_id); 
            echo "</pre>";
            die();
        }

        // Validación correcta del ID
        if (!is_numeric($nuevo_id) || intval($nuevo_id) <= 0) {
            echo "<h1>Error crítico: ID inválido retornado por registrarUsuario()</h1>";
            echo "<h3>ID devuelto:</h3>";
            var_dump($nuevo_id);
            die();
        }

        // Ahora sí: actualizamos el usuario recién creado para guardar el GOOGLE_ID
        // (asumiendo que tu SP lo soporta; si no, debes crear un método aparte)
        $userModel->asociarGoogleId($nuevo_id, $google_id);

        // Limpiar sesiones previas
        unset($_SESSION['errores_login']);
        unset($_SESSION['datos_login']);
        unset($_SESSION['errores_registro']);
        unset($_SESSION['datos_registro']);

        $_SESSION['id_usuario'] = $nuevo_id;
        $_SESSION['nombres'] = $nombres;
        $_SESSION['tipo_usuario'] = 'USUARIO';

        $_SESSION['mensaje'] = '¡Registro exitoso con Google!';
     header('Location: ' . ABSOLUTE_BASE_URL . 'index.php?route=registro');
        exit;

    }

} else {
    // Si no hay 'code' en la URL, el usuario canceló
    $_SESSION['alerta'] = 'Registro cancelado.';
    header('Location: ' . ABSOLUTE_BASE_URL . 'index.php?route=registro');
    exit;
}