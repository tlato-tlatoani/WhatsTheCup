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

// ------------ FILTROS ------------ //

$sede = $_GET['sede'] ?? '';
$orden = $_GET['orden'] ?? 'reciente';

// FILTRAR POR SEDE (busca en el título)
if ($sede !== '') {
    $mundiales = array_filter($mundiales, function($m) use ($sede) {
        return stripos($m['titulo'], $sede) !== false;
    });
}

// ORDENAR POR AÑO
usort($mundiales, function($a, $b) use ($orden) {
    if ($orden === 'antiguo') {
        return $a['anio'] - $b['anio']; // ascendente
    }
    return $b['anio'] - $a['anio']; // descendente
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Fugaz+One&display=swap" rel="stylesheet">
  <title>Dashboard - What's The Cup</title>

  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/Ad_Landing.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/SidebarAdmin.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/Header.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/Fuentes.css">
</head>
<body>

<div id="layout">
   <div id="sidebar-container"></div>

  <div id="pagina-principal">

    <div id="header-mundiales">
      <h1>LISTA DE MUNDIALES</h1>

      <!-- FORMULARIO DE FILTROS -->
      <form method="GET" class="filtros-container">

    <input type="hidden" name="route" value="adlanding">

    <input 
        type="text" 
        name="sede" 
        placeholder="Filtrar por sede (México, Brasil...)"
        value="<?= htmlspecialchars($sede) ?>"
    >

    <select name="orden">
        <option value="reciente" <?= $orden === 'reciente' ? 'selected' : '' ?>>Más reciente</option>
        <option value="antiguo"  <?= $orden === 'antiguo'  ? 'selected' : '' ?>>Más antiguo</option>
    </select>

    <button type="submit" class="btn-filtrar">Aplicar</button>

    <a href="index.php?route=adlanding" class="btn-limpiar">Limpiar</a>

</form>


    </div>

    <div id="mundiales">

     <?php foreach ($mundiales as $m): ?>
        <a href="<?= BASE_URL ?>index.php?route=adinfografia&id=<?= $m['id'] ?>">
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
<script src="<?= BASE_URL ?>public/js/Ad_Landing.js"></script>
</body>
</html>