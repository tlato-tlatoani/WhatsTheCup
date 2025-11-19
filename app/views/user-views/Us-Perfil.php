<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/WhatsTheCup/');
}

require_once dirname(__DIR__, 3) . '/Conexion.php';
require_once PROJECT_ROOT . '/app/controllers/publicaciones_usuario.php';

$conexion = new Conexion();
$conn = $conexion->getConnection();

$id_usuario = $_SESSION['id_usuario'] ?? null;

// Prevenir errores si no hay sesión
$usuario = [];

if ($id_usuario) {
    // CONSULTAR DATOS DEL USUARIO
    $stmt = $conn->prepare("CALL sp_consultar_usuarios(?)");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    $stmt->closeCursor();
}

// Construir nombre completo
$nombre = trim(($usuario['NOMBRES'] ?? '') . ' ' . ($usuario['APELLIDO_P'] ?? ''));

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
  <title>Pantalla Perfil</title>
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Us_Perfil.css">
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Header.css">
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Fuentes.css">
</head>
<body>
  
<form id="form-perfil" action="/WhatsTheCup/index.php" method="POST" enctype="multipart/form-data">

  <div id="header-perfil">

   <div class="perfil-header">
    <!-- Foto de perfil -->
    <div class="upload">
        <?php if (!empty($usuario['IMAGEN_PERFIL'])): ?>
    <img id="foto-de-perfil" 
         src="data:image/png;base64,<?= base64_encode($usuario['IMAGEN_PERFIL']) ?>" 
         alt="Foto de perfil">
<?php else: ?>
    <img id="foto-de-perfil" 
         src="/WhatsTheCup/public/imagenes/FDP.png" 
         alt="Foto por defecto">
<?php endif; ?>
        <!-- <input type="file" id="input-foto" name="IMAGEN_PERFIL" style="display:none;" disabled>
        <button type="button" id="btn-cambiar-foto">Cambiar foto</button> -->
    </div>

    <!-- Saludo / Nombre -->
    <div id="saludo">
        <h1>HOLA,</h1>
        <p id="static-nombre"><?php  echo htmlspecialchars($usuario['NOMBRES']); ?></p>
        <input type="text" name="NOMBRES" id="input-nombre" class="editar" value="<?php echo htmlspecialchars($usuario['NOMBRES']); ?>" style="display:none;" disabled>
    </div>
</div>

      <div id="div-genero">
    <label>Género:</label>
    <p id="static-nombre"><?php echo htmlspecialchars($usuario['GENERO'] ?? 'Genero'); ?></p>
    <select name="GENERO" class="editar" style="display:none;" disabled>
        <option value="F" <?php echo ($usuario['GENERO']=='F') ? 'selected' : ''; ?>>Femenino</option>
        <option value="M" <?php echo ($usuario['GENERO']=='M') ? 'selected' : ''; ?>>Masculino</option>
    </select>
</div>

<div id="div-fecha">
    <i class="bi bi-cake"></i>
        <?php $fecha_db = $usuario['NACIMIENTO'] ?? ''; ?>
        <p id="static-fecha"><?php echo !empty($fecha_db) ? date('d/m/Y', strtotime($fecha_db)) : 'Fecha no disponible'; ?></p> 
    
        <input type="date" name="NACIMIENTO" class="editar" value="<?php echo htmlspecialchars($usuario['NACIMIENTO'] ?? ''); ?>" style="display:none;" disabled>
</div>

<div id="div-pais">
    <label>País:</label>
     <p id="static-nombre"><?php echo htmlspecialchars($usuario['PAIS_ORIGEN'] ?? 'País de Origen no disponible'); ?></p>
    <input type="text" name="PAIS_ORIGEN" class="editar" value="<?php echo htmlspecialchars($usuario['PAIS_ORIGEN']); ?>" style="display:none;" disabled>
</div>

<div id="div-nacionalidad">
    <label>Nacionalidad:</label>
     <p id="static-nombre"><?php echo htmlspecialchars($usuario['NACIONALIDAD'] ?? 'Nacionalidad no disponible'); ?></p>
    <input type="text" name="NACIONALIDAD" class="editar" value="<?php echo htmlspecialchars($usuario['NACIONALIDAD']); ?>" style="display:none;" disabled>
</div>

<div id="div-correo">
    <label style="display:none;">Correo:</label>
    <input type="email" name="CORREO" class="editar" value="<?php echo htmlspecialchars($usuario['CORREO']); ?>" style="display:none;" disabled>
</div>

<div id="div-contra">
    <label style="display: none;">Contraseña</label>
    <input type="password" name="CONTRASENNA" class="editar" value="" style="display:none;" disabled>
</div>


     <div id="botones">
  <button id="btn-editar"
          type="button"
          onclick="window.location.href='/WhatsTheCup/index.php?route=editar_perfil';">
    Editar
  </button>

  <button id="btn-guardar"
          name="btn_actualizar"
          type="submit"
          style="display:none;">
    Guardar
  </button>

  <button id="btn-cerrar"
          type="button"
          onclick="window.location.href='/WhatsTheCup/app/views/user-views/Us-IniciarSesion.php';">
    Cerrar sesión
  </button>
</div>

    </form>

    </div>
  

  

    <?php if (!empty($publicaciones_usuario)): ?>

    <?php foreach ($publicaciones_usuario as $pub): ?>

        <a href="/WhatsTheCup/index.php?route=ver_publicacion&id=<?= $pub['id'] ?>">
            <div class="publicacion">

                <div class="preview">
                    <h1 class="titulo"><?= htmlspecialchars($pub['titulo']) ?></h1>

                    <h2 class="infografia">
                        <?= !empty($pub['mundial_id']) 
                                ? "Infografía #" . htmlspecialchars($pub['mundial_id'])
                                : "" ?>
                    </h2>

                    <p><?= nl2br(htmlspecialchars($pub['descripcion'])) ?></p>
                </div>

                <div class="interaccion">
                    
                    <?php if (!empty($pub['multimedia_base64'])): ?>
                        <img src="data:<?= $pub['tipo_mime'] ?>;base64,<?= $pub['multimedia_base64'] ?>" 
                             class="imagen-post">
                    <?php else: ?>
                        <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-post">
                    <?php endif; ?>

                    <h2><i class="bi bi-heart-fill"></i> 0</h2>
                    <h2><i class="bi bi-chat-right-text"></i> 0</h2>
                </div>
            </div>
        </a>

    <?php endforeach; ?>

<?php else: ?>

    <div class="publicacion">
        <p style="padding:20px; text-align:center; font-size:18px;">
            Aún no tienes publicaciones aprobadas.
        </p>
    </div>

<?php endif; ?>

   



 <nav aria-label="Page navigation example">
  <ul class="pagination">
   <li class="page-item">
     <a class="page-link" href="#" aria-label="Previous">
       <span aria-hidden="true">&laquo;</span>
     </a>


    </li>
     <li class="page-item"><a class="page-link" href="#">1</a></li>
     <li class="page-item"><a class="page-link" href="#">2</a></li>
     <li class="page-item"><a class="page-link" href="#">3</a></li>

    <li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
       <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
 </nav>


<script src="/WhatsTheCup/public/js/Header.js"></script>


</body>
</html> 