<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/WhatsTheCup/');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__, 3) . '/app/models/Model_Mundial.php';

$model = new Model_Mundial();
$mundiales = $model->obtenerTodosLosMundiales();

//$listaMundiales = $model->obtenerMundiales();
// $listaMundiales es un array con todos los registros
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Fugaz+One&display=swap" rel="stylesheet">
  <title>Dashboard - What's The Cup</title>

  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/Us_Landing.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/SidebarUsuario.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/Header.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/Fuentes.css">
</head>
<body>

<div id="layout">
   <div id="sidebar-container"></div>

  <div id="pagina-principal">

    <div id="header-mundiales">
      <h1>LISTA DE MUNDIALES</h1>

      <select>
        <option>Más reciente</option>
        <option>Más antiguo</option>
        <option>Más popular</option>
      </select>
    </div>

    <div id="mundiales">

     <?php foreach ($mundiales as $m): ?>
<a href="<?= BASE_URL ?>index.php?route=usinfografia&id=<?= $m['id'] ?>">
    <div class="card" style="width: 18rem;">
    
        <img 
            src="data:<?= $m['banner_mime'] ?>;base64,<?= $m['banner_base64'] ?>"
            class="card-img-top"
        />

        <div class="card-body">
            <p class="card-text"><?= $m['titulo'] ?> (<?= $m['anio'] ?>)</p>
        </div>

    </div>
</a>
<?php endforeach; ?>

    </div>

  </div>
</div>

<h1 id="footer">Whats The Cup. Todos los derechos reservados</h1>

<script src="<?= BASE_URL ?>public/js/Header.js"></script>
<script src="<?= BASE_URL ?>public/js/Us_Landing.js"></script>
</body>
</html>
