<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'UserModel.php';
require_once __DIR__ . '/api/Validar_Registro.php'; 


$userModel = new UserModel();

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
 header("Location: " . BASE_URL . "index.php?route=iniciarsesion"); 
 exit;
}

// Inicializar errores y mensajes desde la sesión
$errores = $_SESSION['errores_actualizacion'] ?? [];
$mensaje_exito = $_SESSION['mensaje_exito'] ?? '';
$datos_post = $_SESSION['datos_actualizacion'] ?? []; // Datos temporales del formulario fallido
unset($_SESSION['errores_actualizacion'], $_SESSION['mensaje_exito'], $_SESSION['datos_actualizacion']);

// ==========================================================
// LÓGICA DE LECTURA DE DATOS 
// ==========================================================
$usuario = $userModel->consultarUsuarioPorId($id_usuario);

if (!$usuario) {
    // Manejar caso donde el usuario no existe o hubo un error de lectura de BD
    $usuario = []; // Para evitar errores al intentar acceder a indices
    $errores['general'] = "No se pudieron cargar los datos del usuario. Error de conexión/consulta.";
}

if (!empty($datos_post)) {

 $usuario = array_merge($usuario, $datos_post);
}


// ==========================================================
// PROCESAR FORMULARIO DE ACTUALIZACIÓN (POST)
// ==========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_actualizar'])) {

 // Recoger y sanitizar datos del formulario
 $nombres  = htmlspecialchars(trim($_POST['NOMBRES'] ?? ''));
 $apellido_p = htmlspecialchars(trim($_POST['APELLIDO_P'] ?? ''));
 $apellido_m  = htmlspecialchars(trim($_POST['APELLIDO_M'] ?? ''));
 $correo = filter_var(trim($_POST['CORREO'] ?? ''), FILTER_SANITIZE_EMAIL);
 $contrasenna = $_POST['CONTRASENNA'] ?? '';
 $nacimiento  = $_POST['NACIMIENTO'] ?? '';
 
    // Normalizar género
    $genero_input = $_POST['GENERO'] ?? '';
    $genero = '';
 if ($genero_input === "Masculino") {
  $genero = "M";
 } elseif ($genero_input === "Femenino") {
  $genero = "F";
 } 
    
 $nacionalidad = htmlspecialchars(trim($_POST['NACIONALIDAD'] ?? ''));
 $pais_origen = htmlspecialchars(trim($_POST['PAIS_ORIGEN'] ?? ''));
    
    // Asignar datos al array que se enviará al modelo
    $datos_para_modelo = [
        'nombres'      => $nombres,
        'apellido_p'   => $apellido_p,
        'apellido_m'   => $apellido_m,
        'nacimiento'   => $nacimiento,
        'genero'       => $genero,
        'nacionalidad' => $nacionalidad,
        'pais_origen'  => $pais_origen,
        'correo'       => $correo,
    ];
    
 // Validaciones de Controller (se mantienen aquí)
 if (empty($nombres) || empty($apellido_p) || empty($apellido_m) || empty($nacionalidad) || empty($pais_origen) || empty($correo) || empty($nacimiento)) {
  $errores['campos'] = "Todos los campos son obligatorios.";
 }

 // Validar contraseña (si se ingresó una nueva)
 if (!empty($contrasenna)) {
  $validarContrasenna = validarContrasenna($contrasenna); // Asumo que validarContrasenna existe
  if (!$validarContrasenna['valido']) {
   $errores['contrasenna'] = $validarContrasenna['mensaje'];
  }
        // *** IMPORTANTE: Si la validación pasa, usar la nueva contraseña (cruda o hasheada aquí) ***
        $datos_para_modelo['contrasenna'] = $contrasenna;
 } else {
  // Si la contraseña está vacía, no la actualizamos en el SP. 
        // Tu SP debe manejar una contraseña vacía como 'no cambiar'.
  $datos_para_modelo['contrasenna'] = ''; // o $usuario['CONTRASENNA'] si tu SP requiere el valor anterior
 }

 // Validar fecha de nacimiento
 if (!empty($nacimiento)) {
  $validarEdad = validarEdad($nacimiento, EDAD_MINIMA); // Asumo que validarEdad existe
  if (!$validarEdad['valido']) {
   $errores['nacimiento'] = $validarEdad['mensaje'];
  }
 }

 // Procesar imagen de perfil si se subió (Lógica de Controller/I/O)
 $imagen_perfil = null;
 if (isset($_FILES['IMAGEN_PERFIL']['tmp_name']) && $_FILES['IMAGEN_PERFIL']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['IMAGEN_PERFIL']['type'], $allowedTypes)) {
    $imagen_perfil = file_get_contents($_FILES['IMAGEN_PERFIL']['tmp_name']);
        } else {
            $errores['imagen'] = "Tipo de imagen no permitido.";
        }
 }
    $datos_para_modelo['imagen_perfil'] = $imagen_perfil;


 // Si hay errores, guardarlos en sesión y redirigir
 if (!empty($errores)) {
  $_SESSION['errores_actualizacion'] = $errores;
  $_SESSION['datos_actualizacion'] = $_POST;
  header("Location: " . BASE_URL . "index.php?route=perfil"); // Redirige a la ruta del perfil
  exit;
 }

 // ==========================================================
 // LLAMADA AL MODELO PARA EJECUTAR ACTUALIZACIÓN
 // ==========================================================
    $resultado_actualizacion = $userModel->actualizarUsuario($id_usuario, $datos_para_modelo);

 
    // Lógica de respuesta
 if ($resultado_actualizacion === true) {
  $_SESSION['mensaje_exito'] = "Perfil actualizado correctamente.";
        // Actualizar la sesión con los nuevos nombres si es necesario
        $_SESSION['nombres'] = $nombres; 
  header("Location: " . BASE_URL . "index.php?route=perfil"); // Redirige a la ruta del perfil
  exit;

 } else {
  // Error de actualización (el Modelo devuelve un string con el error)
  $_SESSION['errores_actualizacion'] = ['general' => $resultado_actualizacion];
  $_SESSION['datos_actualizacion'] = $_POST;
  header("Location: " . BASE_URL . "index.php?route=perfil"); // Redirige a la ruta del perfil
  exit;
 }
}


?>