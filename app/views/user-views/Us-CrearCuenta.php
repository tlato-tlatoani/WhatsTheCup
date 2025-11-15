<?php
// Al inicio de /app/views/user-views/Us-CrearCuenta.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$errores = $_SESSION['errores_registro'] ?? [];
$datos = $_SESSION['datos_registro'] ?? [];
unset($_SESSION['errores_registro']); // Limpia los errores después de leerlos
unset($_SESSION['datos_registro']);   // Limpia los datos después de leerlos
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
          <label>
            <input type="email" name="CORREO" placeholder="Correo electrónico" required>
          </label>
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


          <label class="full">
            <input type="text" name="PAIS_ORIGEN" placeholder="País de nacimiento" required>
          </label>

            <label class="full">
            <input type="text" name="NACIONALIDAD" placeholder="Nacionalidad" required>
          </label>

          <!-- Botón enviar -->
          <div class="btn">
            <button type="submit" name="btn_registrar" class="btn-text">REGISTRARSE</button>
          </div>
        </form>


      </div>
    </div>
  </div>

<script src="/WhatsTheCup/public/js/Us_CrearCuenta.js"></script> 
</body>

</html>