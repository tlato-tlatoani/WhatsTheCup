<?php

// --- Habilitar errores para depuración (¡Solo en desarrollo!) ---
error_reporting(E_ALL);
ini_set('display_errors', 1);
// -----------------------------------------------------------------

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Conexion.php'; // Ruta más robusta
require_once __DIR__ . '/api/Validar_Registro.php'; // Ruta relativa al directorio actual

// Asegúrate que BASE_URL esté definida globalmente (ej. en index.php o un config.php)
// Si no, defínela aquí temporalmente para probar: define('BASE_URL', '/WhatsTheCup/');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Limpiar errores previos al iniciar el proceso
unset($_SESSION['errores_registro']);
unset($_SESSION['datos_registro']); // Limpiar datos viejos también

if (isset($_POST['btn_registrar'])) {

    // --- VALIDACIONES (Como las tenías) ---
    $erroresValidacion = [];
    $esValido = true;

    $contrasenna = $_POST['CONTRASENNA'] ?? null;
    $fechaNacimiento = $_POST['NACIMIENTO'] ?? null;

    // Validar contraseña
    if ($contrasenna === null || $contrasenna === '') {
        $esValido = false;
        $erroresValidacion['contrasenna'] = 'La contraseña es requerida.';
    } else {
        $validacionPass = validarContrasenna($contrasenna);
        if (!$validacionPass['valido']) {
            $esValido = false;
            $erroresValidacion['contrasenna'] = $validacionPass['mensaje'];
        }
    }

    // Validar fecha de nacimiento y edad
    if ($fechaNacimiento === null || $fechaNacimiento === '') {
        $esValido = false;
        $erroresValidacion['nacimiento'] = 'La fecha de nacimiento es requerida.';
    } else {
        $validacionEdad = validarEdad($fechaNacimiento, EDAD_MINIMA);
        if (!$validacionEdad['valido']) {
            $esValido = false;
            $erroresValidacion['nacimiento'] = $validacionEdad['mensaje'];
        }
    }
    // --- FIN VALIDACIONES ---


    // --- Si hubo errores de validación, redirigir ---
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

    // --- Si las validaciones PASARON, continuar con el registro ---

    // ==========================================================
    // SANITIZAR DATOS 
    // ==========================================================
    $NOMBRES = htmlspecialchars($_POST['NOMBRES'] ?? '');
    $APELLIDO_P = htmlspecialchars($_POST['APELLIDO_P'] ?? '');
    $APELLIDO_M = htmlspecialchars($_POST['APELLIDO_M'] ?? '');
    $NACIMIENTO = $_POST['NACIMIENTO']; // Ya validada
    $GENERO_INPUT = $_POST['GENERO'] ?? '';
    $GENERO = '';
        if ($GENERO_INPUT === "Masculino") {
             $GENERO = "M";
        } elseif ($GENERO_INPUT === "Femenino") {
             $GENERO = "F";
        } // Considera añadir un 'else' para otros casos o si es requerido
    $NACIONALIDAD = htmlspecialchars($_POST['NACIONALIDAD'] ?? '');
    $PAIS_ORIGEN = htmlspecialchars($_POST['PAIS_ORIGEN'] ?? '');
    $CORREO = filter_var($_POST['CORREO'] ?? '', FILTER_SANITIZE_EMAIL);
    // $CONTRASENNA ya la tenemos en la variable $contrasenna desde la validación

    // // *** MEJORA: Hashear la contraseña ***
    // $hashContrasenna = password_hash($c, PASSWORD_DEFAULT);
    // // **********************************

    // ==========================================================
    // PROCESAR IMAGEN DE PERFIL
    // ==========================================================
    $imagen_perfil_blob = NULL;
    if (isset($_FILES['IMAGEN_PERFIL']) && $_FILES['IMAGEN_PERFIL']['error'] === UPLOAD_ERR_OK) {
        // Validación básica de tipo de imagen (opcional pero recomendado)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['IMAGEN_PERFIL']['type'], $allowedTypes)) {
             $imagen_perfil_blob = file_get_contents($_FILES['IMAGEN_PERFIL']['tmp_name']);
        } else {
             // Si el tipo no es válido, puedes decidir si parar o continuar sin imagen
             $_SESSION['errores_registro']['imagen'] = 'Tipo de imagen no permitido.';
             $_SESSION['datos_registro'] = $_POST;
             unset($_SESSION['datos_registro']['CONTRASENNA']);
             if (!defined('BASE_URL')) {
                define('BASE_URL', '/WhatsTheCup/');
            }

             header("Location: " . BASE_URL . "index.php?route=registro");
             exit;
        }

    } elseif (isset($_FILES['IMAGEN_PERFIL']) && $_FILES['IMAGEN_PERFIL']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Si hubo un error al subir (diferente a "no se subió archivo")
        $_SESSION['errores_registro']['imagen'] = 'Error al subir la imagen de perfil. Código: ' . $_FILES['IMAGEN_PERFIL']['error'];
        $_SESSION['datos_registro'] = $_POST;
        unset($_SESSION['datos_registro']['CONTRASENNA']);
        if (!defined('BASE_URL')) {
            define('BASE_URL', '/WhatsTheCup/');
        }

        header("Location: " . BASE_URL . "index.php?route=registro");
        exit;
    }


    // ==========================================================
    // CONEXIÓN Y LLAMADA AL STORED PROCEDURE
    // ==========================================================
    $conexion = null; // Inicializar fuera del try
    $stmt = null;     // Inicializar fuera del try

    try {
        $conexion = new Conexion();
        $pdo = $conexion->getConnection();

        // Asegúrate que tu columna en la BD sea suficientemente grande para el hash (ej. VARCHAR(255))
        $sql = "CALL sp_registrar_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; // 10 parámetros
        $stmt = $pdo->prepare($sql);



        // Verifica que todos los bindParam coincidan con tu SP en orden y tipo esperado
        $stmt->bindParam(1, $NOMBRES, PDO::PARAM_STR);
        $stmt->bindParam(2, $APELLIDO_P, PDO::PARAM_STR);
        $stmt->bindParam(3, $APELLIDO_M, PDO::PARAM_STR);
        $stmt->bindParam(4, $NACIMIENTO, PDO::PARAM_STR); // MySQL maneja bien las fechas como string YYYY-MM-DD
        $stmt->bindParam(5, $GENERO, PDO::PARAM_STR);
        $stmt->bindParam(6, $NACIONALIDAD, PDO::PARAM_STR);
        $stmt->bindParam(7, $PAIS_ORIGEN, PDO::PARAM_STR);
        $stmt->bindParam(8, $CORREO, PDO::PARAM_STR);
        $stmt->bindParam(9, $contrasenna, PDO::PARAM_STR); // *** Usar el HASH ***
                if ($imagen_perfil_blob === NULL) {
            $stmt->bindValue(10, null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(10, $imagen_perfil_blob, PDO::PARAM_LOB);
        }

        error_log("Ejecutando SP con datos: $NOMBRES, $CORREO, blob=" . ($imagen_perfil_blob ? 'sí' : 'no'));

        $resultado = $stmt->execute(); // Captura el resultado

        if (!$resultado) {
            $errorInfo = $stmt->errorInfo();
            error_log("Error SQL: " . print_r($errorInfo, true));
        }

        if ($resultado) {
             // Registro exitoso: Limpiar datos de sesión y redirigir
            unset($_SESSION['datos_registro']); // Ya no se necesitan los datos viejos
            // Ya habíamos limpiado errores al principio, así que no hace falta aquí.
            if (!defined('BASE_URL')) {
                define('BASE_URL', '/WhatsTheCup/');
            }

            header("Location: " . BASE_URL . "index.php?route=iniciarsesion&registro=exitoso");
            exit;
        } else {
             // execute() devolvió false, hubo un error pero no lanzó excepción PDOException
             $errorInfo = $stmt->errorInfo();
             $_SESSION['errores_registro'] = ['general' => 'Error al ejecutar el registro en la BD. Código: ' . ($errorInfo[1] ?? 'N/A') . ' Mensaje: ' . ($errorInfo[2] ?? 'Desconocido')];
             $_SESSION['datos_registro'] = $_POST;
             unset($_SESSION['datos_registro']['CONTRASENNA']);

             if (!defined('BASE_URL')) {
                define('BASE_URL', '/WhatsTheCup/');
            }

             header("Location: " . BASE_URL . "index.php?route=registro");
             exit;
        }


    } catch (PDOException $e) {
        // Error durante la conexión o preparación/ejecución
        // Guardar error detallado para depuración (puedes loggearlo a un archivo en producción)
        $errorMessage = $e->getMessage();
        if ($stmt) {
             $errorInfo = $stmt->errorInfo();
             $errorMessage .= " | SQLSTATE: " . ($errorInfo[0] ?? 'N/A') . " | Driver Code: " . ($errorInfo[1] ?? 'N/A') . " | Driver Message: " . ($errorInfo[2] ?? 'N/A');
        }

        // Mostrar un mensaje genérico al usuario, pero guardar el detalle
        $_SESSION['errores_registro'] = ['general' => 'Error interno al registrar el usuario. Por favor, contacta al administrador.'];
        // $_SESSION['errores_registro'] = ['general' => 'Error BD: ' . $errorMessage]; // <- Solo para depuración
        error_log('Error en registro_usuario.php: ' . $errorMessage); // Log real del error

        $_SESSION['datos_registro'] = $_POST; // Guarda datos para rellenar
        unset($_SESSION['datos_registro']['CONTRASENNA']);

        if (!defined('BASE_URL')) {
            define('BASE_URL', '/WhatsTheCup/');
        }


        header("Location: " . BASE_URL . "index.php?route=registro");
        exit;
    } finally {
        // Cerrar cursor y conexión si están abiertos
        if ($stmt) {
            $stmt->closeCursor();
        }
        // $pdo = null; // PDO cierra la conexión automáticamente al destruir el objeto
        if ($conexion) {
            // Si tu clase Conexion tiene un método para cerrar explícitamente, llámalo aquí.
            // Ejemplo: $conexion->closeConnection();
        }
    }

} else {
    // Si no se envió el formulario, redirigir
    echo "Registro exitoso";

    if (!defined('BASE_URL')) {
        define('BASE_URL', '/WhatsTheCup/');
    }

    //exit;
    header("Location: " . BASE_URL . "index.php?route=registro");
    exit;
}
?>