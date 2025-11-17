<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/WhatsTheCup/');
}

require_once dirname(__DIR__, 3) . '/Conexion.php';

$conexion = new Conexion();
$conn = $conexion->getConnection();

$id_usuario = $_SESSION['id_usuario'] ?? null;

// Evitar errores si no hay sesión
$usuario = [];

// Solo consultar si existe sesión
if ($id_usuario) {
    try {
        $stmt = $conn->prepare("CALL sp_consultar_usuarios(?)");
        $stmt->execute([$id_usuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        $stmt->closeCursor();
    } catch (Exception $e) {
        // Si falla, no truenes. Solo deja $usuario vacío.
        $usuario = [];
    }
}

// Foto de perfil
if (!empty($usuario['IMAGEN_PERFIL'])) {
    $foto_perfil = "data:image/png;base64," . base64_encode($usuario['IMAGEN_PERFIL']);
} else {
    $foto_perfil = "/WhatsTheCup/public/imagenes/FDP.png";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Usuario</title>

  <link href="https://fonts.googleapis.com/css2?family=Fugaz+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Us-editar_perfil.css">
</head>

<body>

  <div class="container">

    <div class="header">
      <div class="profile-pic">
        <div class="profile-text">FOTO DE PERFIL</div>

        <img id="foto-preview"
             src="<?= $foto_perfil ?>"
             alt="Foto de perfil">
      </div>

      <h2>HELLO <?= strtoupper($usuario['NOMBRES'] ?? '') ?></h2>
    </div>


    <!-- FORMULARIO DE EDICIÓN -->
   
<form action="/WhatsTheCup/app/controllers/actualizar_usuario.php"
      method="POST"
      enctype="multipart/form-data">

      <div class="upload">
        <input type="file" id="file" name="IMAGEN_PERFIL" style="display:none;">
        <button type="button" class="button-two" onclick="document.getElementById('file').click()">
          SELECCIONAR FOTO
        </button>
      </div>

      <div class="form-grid">

        <input class="input-large" type="text" 
               name="NOMBRES"
               placeholder="Nombre y Apellido..."
               value="<?= $usuario['NOMBRES'] ?? '' ?>">
<input type="text" 
       name="APELLIDO_P"
       placeholder="Apellido paterno..."
       value="<?= $usuario['APELLIDO_P'] ?? '' ?>">

<input type="text" 
       name="APELLIDO_M"
       placeholder="Apellido materno..."
       value="<?= $usuario['APELLIDO_M'] ?? '' ?>">
        <input type="email" 
               name="CORREO"
               placeholder="EMAIL"
               value="<?= $usuario['CORREO'] ?? '' ?>">


<input type="password" 
       name="CONTRASENNA"
       value="<?= htmlspecialchars($usuario['CONTRASENNA'] ?? '') ?>"
       placeholder="Contraseña (opcional)">


        <input type="date"
               name="NACIMIENTO"
               value="<?= $usuario['NACIMIENTO'] ?? '' ?>">

        <input type="text" 
               name="NACIONALIDAD"
               placeholder="Nacionalidad..."
               value="<?= $usuario['NACIONALIDAD'] ?? '' ?>">

      <select name="GENERO">
             <option value="">Seleccione género</option>
             <option value="Masculino" <?= ($usuario['GENERO'] === "M") ? "selected" : "" ?>>Masculino</option>
             <option value="Femenino"  <?= ($usuario['GENERO'] === "F") ? "selected" : "" ?>>Femenino</option>
       </select>
        <input type="text"
               name="PAIS_ORIGEN"
               placeholder="País de Nacimiento..."
               value="<?= $usuario['PAIS_ORIGEN'] ?? '' ?>">
      </div>


      <<div class="buttons">
        <!-- SALIR: vuelve al perfil -->
        <button type="button"
                onclick="window.location.href='<?= BASE_URL ?>index.php?route=perfil';">
          SALIR
        </button>

        <!-- GUARDAR: ENVÍA EL FORMULARIO AL CONTROLADOR -->
        <button type="submit" name="btn_actualizar" value="1">
          GUARDAR
        </button>
      </div>

    </form>

  </div>

  <script src="/WhatsTheCup/public/js/editar_usuario.js"></script>
</body>
</html>
