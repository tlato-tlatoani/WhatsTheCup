<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si la vista NO definió $usuario, lo cargamos aquí
if (!isset($usuario) || !is_array($usuario) || empty($usuario)) {

    // Necesitamos la conexión a la BD
    // SidebarUsuario.php está en: app/views/
    // Conexion.php está en la raíz: WhatsTheCup/Conexion.php
    require_once dirname(__DIR__, 2) . '/Conexion.php';

    $conexion = new Conexion();
    $conn = $conexion->getConnection();

    $id_usuario = $_SESSION['id_usuario'] ?? null;
    $usuario = [];

    if ($id_usuario) {
        $stmt = $conn->prepare("CALL sp_consultar_usuarios(?)");
        $stmt->execute([$id_usuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        $stmt->closeCursor();
    }
}

// ===== Construir nombre =====
$nombre = "Usuario";

if (isset($usuario['NOMBRES']) || isset($usuario['APELLIDO_P'])) {
    $nombreConstruido = trim(
        ($usuario['NOMBRES'] ?? '') . ' ' .
        ($usuario['APELLIDO_P'] ?? '')
    );

    if ($nombreConstruido !== '') {
        $nombre = $nombreConstruido;
    }
}

// ===== Construir foto =====
$foto = "/WhatsTheCup/public/imagenes/FDP.png";

if (!empty($usuario['IMAGEN_PERFIL'])) {
    $foto = "data:image/png;base64," . base64_encode($usuario['IMAGEN_PERFIL']);
}
?>

<div class="sidebar">
  <img src="<?= $foto ?>" class="foto-perfil" alt="Foto de perfil">
    <h1>Admin</h1>

  <h1><?= htmlspecialchars($nombre) ?></h1>

<a href="/WhatsTheCup/app/views/admin-views/Ad-AprobarPosts.php">
    <button>Publicaciones</button>
  </a>

  <a href="/WhatsTheCup/app/views/admin-views/Ad-Categorias.php"> 
    <button>Categorías</button> 
  </a>

  <a href="/WhatsTheCup/app/views/admin-views/Ad-AgregarMundial.php">
  <button id="AgregarMundial">Agregar mundial</button>
  </a>

  <a href="/WhatsTheCup/index.php?route=iniciarsesion">
      <button>Cerrar sesión</button>
  </a>
</div>
