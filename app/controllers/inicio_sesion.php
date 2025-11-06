<?php


if (!defined('PROJECT_ROOT')) {
    http_response_code(500); exit("Error de configuración: Dependencias no cargadas.");
}

// Incluir la clase de conexión
require_once PROJECT_ROOT . DIRECTORY_SEPARATOR . 'Conexion.php';
// Asegurarse de iniciar la sesión si el index no lo hizo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['btn_iniciar_sesion'])) {
    
    $CORREO      = filter_var(trim($_POST['CORREO'] ?? ''), FILTER_SANITIZE_EMAIL);
    $CONTRASENNA = $_POST['CONTRASENNA'] ?? '';
    
    $errores = [];

    if (!filter_var($CORREO, FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = "Correo no válido.";
    }
    if (empty($CONTRASENNA)) {
        $errores['contrasenna'] = "Debe ingresar una contraseña.";
    }

    if (!empty($errores)) {
        $_SESSION['errores_login'] = $errores;
        $_SESSION['datos_login'] = ['CORREO' => $CORREO];
        // Redirige de vuelta a la vista de inicio de sesión con errores
        header("Location: " . BASE_URL . "index.php?route=iniciarsesion&status=validation_error");
        exit();
    }

    try {
        $conexion = new Conexion();
        $pdo = $conexion->getConnection(); 

        //LLAMADA AL STORED PROCEDURE (BUSCA POR CORREO Y CONTRASEÑA)
        $sql = "CALL sp_iniciar_sesion(?, ?)"; 
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$CORREO, $CONTRASENNA]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // INICIO DE SESIÓN EXITOSO
            $_SESSION['id_usuario'] = $usuario['ID_USUARIO'];
            $_SESSION['nombres'] = $usuario['NOMBRES'];
            $_SESSION['tipo_usuario'] = $usuario['TIPO_USUARIO'];
            
            //aRedirección basada en el tipo de usuario
            if ($usuario['TIPO_USUARIO'] === 'ADMIN') {
                header("Location: " . BASE_URL . "index.php?route=adlanding");
            } else {
                header("Location: " . BASE_URL . "index.php?route=landing");
            }
            exit();

        } else {
            // CREDENCIALES INVÁLIDAS
            $_SESSION['errores_login'] = ['general' => "Correo o contraseña incorrectos."];
            $_SESSION['datos_login'] = ['CORREO' => $CORREO];
            header("Location: " . BASE_URL . "index.php?route=iniciarsesion&status=invalid_credentials");
            exit();
        }

    } catch (PDOException $e) {
        // Error de Base de Datos
        $_SESSION['errores_login'] = ['general' => "Error de BD: Falló la conexión o consulta."];
        $_SESSION['datos_login'] = ['CORREO' => $CORREO];
        header("Location: " . BASE_URL . "index.php?route=iniciarsesion&status=db_error");
        exit();
    }
}
?>