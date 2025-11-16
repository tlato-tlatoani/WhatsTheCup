<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// =================================================================
// ZONA A: INICIALIZACIÓN DE GOOGLE OAUTH
// =================================================================
// Carga la librería de Composer (necesaria para la clase Google_Client)
require_once PROJECT_ROOT . '/vendor/autoload.php';

// Reemplaza con tus CREDENCIALES
define('GOOGLE_CLIENT_ID', '442951345014-535grmggqi08tpjgvts530p408j9f0rb.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost/WhatsTheCup/index.php?route=google-callback');

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope('email');
$client->addScope('profile'); // Para obtener el nombre y apellido

// Genera la URL a la que el botón debe apuntar
$google_login_url = $client->createAuthUrl();
// =================================================================

$errores = $_SESSION['errores_registro'] ?? [];
$datos = $_SESSION['datos_registro'] ?? [];
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Cuenta - What's The Cup</title>
 <link rel="stylesheet" href="/WhatsTheCup/public/css/Fuentes.css">
 <link rel="stylesheet" href="/WhatsTheCup/public/css/Us_CrearCuenta.css">
</head>

<body>
  <div class="container">
    <!-- Panel izquierdo -->
    <div class="left">
      <div class="texto">
        <h1 class="titulo">WHATS THE CUP:</h1>
        <h2 class="subtitulo">EL ARCHIVO DEFINITIVO DEL MUNDIAL</h2>
      </div>
    </div>

    <!-- Panel derecho -->
    <div class="right">
      <div class="form-box">
        <h2 class="titulo2">CREAR CUENTA</h2>

        <!-- Formulario -->
        <form action="/WhatsTheCup/index.php" method="POST" enctype="multipart/form-data">
          
        <!-- Subir foto -->
        <div class="upload">
          <div class="circle" id="profilePicCircle"></div>
          <input type="file" id="file" name="IMAGEN_PERFIL" style="display:none;">
          <button type="button" onclick="document.getElementById('file').click()">Subir foto</button>
        </div>

        <div id="nombres-div">
            <label id="label-nombres">
                <input type="text" name="NOMBRES" placeholder="Nombre(s)" required>
            </label>
      </div>
          
          <label>
            <input type="text" name="APELLIDO_P" placeholder="Apellido Paterno" required>
          </label>
          <label>
            <input type="text" name="APELLIDO_M" placeholder="Apellido Materno" required>
          </label>

          <div id="mails" class="input-group-correo"> 
            <label>
                   <input type="email" name="CORREO" id="CORREO" placeholder="Correo electrónico" required autocomplete="off">
            </label>
           <ul id="suggestions" class="suggestions-box" style="display:none;"> </ul>

          </div>

          <label>
            <input type="password" name="CONTRASENNA" placeholder="Contraseña" required>
          </label>
          <?php if (isset($errores['contrasenna'])): ?>
              <p class="error-message"><?php echo htmlspecialchars($errores['contrasenna']); ?></p>
          <?php endif; ?>
          <label id="label-nacimiento">
            Fecha de nacimiento
            <input type="date" name="NACIMIENTO" required>
          </label>
          <?php if (isset($errores['nacimiento'])): ?>
            <p class="error-message"><?php echo htmlspecialchars($errores['nacimiento']); ?></p>
          <?php endif; ?>

          <!-- Género -->
          <div class="genero">
            <label class="radio-custom">
              <input type="radio" name="GENERO" value="Femenino" required>
              <span class="check"></span>
              Femenino
            </label>
            <label class="radio-custom">
              <input type="radio" name="GENERO" value="Masculino" required>
              <span class="check"></span>
              Masculino
            </label>
          </div>

    <div id="country-origin-group" class="input-group-correo">
       <label class="full">
         <input type="text" name="PAIS_ORIGEN" id="countryInput" placeholder="País de nacimiento" required autocomplete="off">
      </label>
      <ul id="countrySuggestions" class="suggestions-box" style="display:none;"></ul>
    </div>


    <div id="nation-origin-group" class="input-group-correo">
      <label class="full">
         <input type="text" name="NACIONALIDAD" id="nationInput" placeholder="Nacionalidad" required autocomplete="off">
      </label>
      <ul id="nationSuggestions" class="suggestions-box" style="display:none;"></ul>
   </div>


          <!-- Botón enviar -->
          <div class="btn">
            <button type="submit" name="btn_registrar" class="btn-text">REGISTRARSE</button>
          </div>
        </form>

           <a href="<?php echo htmlspecialchars($google_login_url); ?>" class="btn-google">
                    <!-- Icono de Google. Asumo que tienes una imagen SVG o PNG en esta ruta -->
                    <img src="/WhatsTheCup/public/img/google-icon.svg" alt="Google Icon" class="google-icon"> 
                    Registrarse con Google
                </a>

      </div>
    </div>
  </div>

<script src="/WhatsTheCup/public/js/Us_CrearCuenta.js"></script> 
<script src="/WhatsTheCup/public/js/Lista_Paises.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>


<?php if (isset($errores['correo'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    Swal.fire({
        icon: "error",
        title: "Correo en uso",
        text: "<?php echo $errores['correo']; ?>",
        confirmButtonText: "Entendido"
    });
});
</script>
<?php endif; ?>

<?php 
// Ahora sí podemos limpiar
unset($_SESSION['errores_registro']);
unset($_SESSION['datos_registro']);
?>


</html>