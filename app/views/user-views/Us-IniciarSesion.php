<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Fuentes.css">
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Us_IniciarSesion.css">

  <title>Sign In - WHATS THE CUP</title>

</head>
<body>
  <div class="main-container">
    <div class="left">
      <h1 class="titulo">WHATS THE CUP:</h1>
      <h2 class="subtitulo">EL ARCHIVO DEFINITIVO DEL MUNDIAL</h2>
    </div>
    <div class="right">
      <div class="card">
        <h2 class="card-title">HOLA DE NUEVO...</h2>
       <form action="/WhatsTheCup/index.php" method="POST">
          <label for="email">CORREO ELECTRONICO</label>
          <input type="email" id="email" name="CORREO" placeholder="correo@ejemplo.com" required>
          
          <label for="password">CONTRASEÑA</label>
          <input type="password" id="password" name="CONTRASENNA" placeholder="Contraseña" required>
          
          <button type="submit" name="btn_iniciar_sesion">INICIAR SESION</button>
          
          <p><a href="#">¿Olvidaste tu contraseña?</a></p>
          <p class="register-text">¿No tienes una cuenta?</p>
          <p><a href="/WhatsTheCup/app/views/user-views/Us-CrearCuenta.php" class="register-link">REGÍSTRATE</a></p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>

<!-- <?php

$errores = $_SESSION['errores_login'] ?? [];
$correo_previo = $_SESSION['datos_login']['CORREO'] ?? '';

unset($_SESSION['errores_login'], $_SESSION['datos_login']);
?> -->
