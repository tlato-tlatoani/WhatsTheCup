<?php
// 1. DEFINIR LA RAÍZ DEL PROYECTO
define('PROJECT_ROOT', __DIR__);

define('BASE_URL', '/WhatsTheCup/'); 

if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}


// ====================================================================
// A. MANEJADOR DE PETICIONES POST (Lógica: Recibir formulario)
// ====================================================================

// Verificamos si la solicitud es un envío de formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if (isset($_POST['btn_registrar'])) {
        require_once PROJECT_ROOT . '/app/controllers/registro_usuario.php';
        exit();
    }
    
    if (isset($_POST['btn_iniciar_sesion'])) {
        require_once PROJECT_ROOT . '/app/controllers/inicio_sesion.php';
        exit();
    }

    if (isset($_POST['btn_actualizar'])) {
        require_once PROJECT_ROOT . '/app/controllers/actualizar_usuario.php';
        exit();
    }

    if (isset($_POST['btn_agregar_mundial'])) {
        require_once PROJECT_ROOT . '/app/controllers/agregar_mundial.php';
        exit();
    }
}

    // Aquí se agregarían otras lógicas de POST 


// ====================================================================
// B. MANEJADOR DE PETICIONES GET (Lógica: Mostrar vistas)
// ====================================================================

// 1. Determinar la ruta solicitada por el usuario (usando un parámetro GET simple)
// Ejemplo de URL: http://localhost/WhatsTheCup/public/index.php?route=registro
$route = $_GET['route'] ?? 'landing'; // Valor por defecto: 'landing' (para Us-Landing.php)


// 2. Mapear la ruta a un archivo de vista específico
switch ($route) {
    case 'landing':
        $view_path = PROJECT_ROOT . '/app/views/user-views/Us-Landing.php';
        break;
    case 'registro':
        $view_path = PROJECT_ROOT . '/app/views/user-views/Us-CrearCuenta.php';
        break;
    case 'iniciarsesion':
        $view_path = PROJECT_ROOT . '/app/views/user-views/Us-IniciarSesion.php';
        break;
    case 'adlanding':
        $view_path = PROJECT_ROOT . '/app/views/admin-views/Ad-Landing.php';
        break;
    case 'perfil':
        $view_path = PROJECT_ROOT . '/app/views/user-views/Us-Perfil.php';
        break;
    case 'editar_perfil':
        $view_path = PROJECT_ROOT . '/app/views/user-views/Us-editar_perfil.php';
        break;
        
  case 'adagregarmundial':
    $view_path = PROJECT_ROOT . '/app/views/admin-views/Ad-AgregarMundial.php';
    break;



    default:
        header("HTTP/1.0 404 Not Found");
        $view_path = PROJECT_ROOT . '/app/views/error-views/404.php';
        break;
}

// 3. Cargar la Vista
if (file_exists($view_path)) {
    // La vista se encarga de incluir sus propios Header/Sidebar.
    require_once $view_path;
} else {
    // Esto solo ocurre si la ruta estaba definida, pero el archivo falta.
    header("HTTP/1.0 500 Internal Server Error");
    echo "<h1>Error 500</h1><p>El archivo de vista para la ruta '{$route}' no se encontró.</p>";
}

?>