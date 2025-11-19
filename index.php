<?php
error_reporting(E_ALL); 
ini_set('display_errors', 0); // No muestra en pantalla
ini_set('log_errors', 1); // Activa el registro en archivo
ini_set('error_log', __DIR__ . '/php_error.log');
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

    if (isset($_POST['btn_agregar_categoria'])) {
       require_once PROJECT_ROOT . '/app/controllers/agregar_categorias.php';
       exit();
    }

    if (isset($_POST['btn_crear_publicacion'])) {
       require_once PROJECT_ROOT . '/app/controllers/agregar_publicacion.php';
       exit();
    }

     if (isset($_POST['btn-like'])) {
       require_once PROJECT_ROOT . '/app/controllers/like_post.php';
       exit();
    }
    if($_GET['route'] === 'like_post'){
        require_once PROJECT_ROOT . '/app/controllers/like_post.php';
        exit;
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


    case 'google-callback':
        require PROJECT_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'Google_Controller.php';
        // Si el controlador no redirige, la ejecución seguirá al 'default' (404).
        exit;

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
    case 'usinfografia':
    $view_path = PROJECT_ROOT . '/app/views/user-views/Us-Infografia.php';
    break;

    case 'adinfografia':
       $view_path = PROJECT_ROOT . '/app/views/admin-views/Ad-Infografia.php';
       break;

    case 'adcategorias':
    $view_path = PROJECT_ROOT . '/app/views/admin-views/Ad-Categorias.php';
    break;

    case 'ajax_mundial_modal':
    require_once PROJECT_ROOT . '/app/controllers/obtener_mundiales_modal.php';
    exit;
    break;

    case 'ad_aprobar_posts':
        // Cargar el controlador que consulta los datos
        require_once PROJECT_ROOT . '/app/controllers/publicaciones_pendientes.php';
        // Definir la vista (los datos ya están disponibles gracias al controlador)
        $view_path = PROJECT_ROOT . '/app/views/admin-views/Ad-AprobarPosts.php';
        break;

        case 'aprobar_post':
        case 'rechazar_post':
            require_once PROJECT_ROOT . '/app/controllers/aprobar_rechazar_publicacion.php';
            break;
        
            case 'us_infografia':
                    $view = 'app/views/user-views/Us-Infografia.php';
                break;


    case 'ver_publicacion':
    require_once PROJECT_ROOT . '/app/controllers/ver_publicacion.php';
    $view_path = PROJECT_ROOT . '/app/views/user-views/Us-Post.php';
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