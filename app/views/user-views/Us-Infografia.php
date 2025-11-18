<?php
require_once dirname(__DIR__, 3) . '/app/models/Model_Mundial.php';

$model = new Model_Mundial();
$modelCategorias = new Model_Categorias();

$id = $_GET['id'] ?? null;
$mundial = $model->obtenerMundialPorId($id);

if (!$mundial) {
    echo "Mundial no encontrado.";
    exit;
}
$categorias = $modelCategorias->obtenerCategorias();
$detalles = json_decode($mundial['detalles'], true);
$equipos = explode(",", $mundial['equipos']);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/WhatsTheCup/public/css/Us_Infografia.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/SidebarUsuario.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Header.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Fuentes.css">

    <title><?= htmlspecialchars($mundial['titulo']) ?></title>
</head>

<body>
<?php include dirname(__DIR__) . '/Header.php'; ?>

<div id="layout">

<!-- SIDEBAR -->
<div id="sidebar-container"></div>

<div id="pagina-principal">

     <!-- HEADER DEL MUNDIAL -->
     <div id="introduccion">

        <div id="header-titulo">
            <h1><?= htmlspecialchars($mundial['titulo']) ?></h1>

            <button id="btn-contribuir">Contribuir</button>
        </div>

        <div id="header-intro">
            <?php if (!empty($mundial['banner_base64'])): ?>
                <img src="data:<?= $mundial['banner_mime'] ?>;base64,<?= $mundial['banner_base64'] ?>" class="imagen-post">
            <?php else: ?>
                <img src="<?= $banner ?>" class="imagen-post">

            <?php endif; ?>

            <p><?= nl2br(htmlspecialchars($mundial['DESCRIPCION'])) ?></p>
        </div>
     </div>


     <!-- INFO DEL MUNDIAL -->
     <div id="datos-mundial">

        <div class="card-datos-mundial">
    <h2>Datos del mundial</h2>

    <div class="fila-dato"><span class="label">Año:</span> <span><?= $mundial['anio'] ?></span></div>
    <div class="fila-dato"><span class="label">Campeón:</span> <span><?= $detalles['campeon'] ?></span></div>
    <div class="fila-dato"><span class="label">Subcampeón:</span> <span><?= $detalles['subcampeon'] ?></span></div>
    <div class="fila-dato"><span class="label">Marcador:</span> <span><?= $detalles['marcador'] ?></span></div>
    <div class="fila-dato"><span class="label">Goleador:</span> <span><?= $detalles['goleador'] ?></span></div>
    <div class="fila-dato"><span class="label">Cantante:</span> <span><?= $detalles['cantante'] ?></span></div>
</div>

<div class="card-equipos">
    <h2>Equipos participantes</h2>
    <ul>
        <?php foreach ($equipos as $eq): ?>
            <li><?= htmlspecialchars($eq) ?></li>
        <?php endforeach; ?>
    </ul>
</div>


     </div>
<!-- IMÁGENES DEL MUNDIAL -->

 <div id="imagenes-mundial">

    <!-- COPA DEL MUNDIAL -->
    <div class="imagen-card">
        <?php if (!empty($mundial['copa_base64'])): ?>
            <img src="data:<?= $mundial['copa_mime'] ?>;base64,<?= $mundial['copa_base64'] ?>" class="imagen-post">
        <?php else: ?>
            <p>Sin imagen de copa</p>
        <?php endif; ?>
    </div>

    <!-- MASCOTA -->
    <div class="imagen-card">
        <?php if (!empty($mundial['mascota_base64'])): ?>
            <img src="data:<?= $mundial['mascota_mime'] ?>;base64,<?= $mundial['mascota_base64'] ?>" class="imagen-post">
        <?php else: ?>
            <p>Sin imagen de mascota</p>
        <?php endif; ?>
    </div>

</div>



     <!-- PUBLICACIONES / INFOGRAFÍAS -->
     
<div id="publicaciones">

    <a href="/WhatsTheCup/app/views/admin-views/Ad-Post.php">
    <div class="publicacion">
        <div class="preview">
            <h1 class="titulo">LOREM IPSUM</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
            Cras nec volutpat justo. Vestibulum at ante at dolor lacinia sollicitudin.</p>
        </div>

        <div class="interaccion">
            <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-post">
            <h2><i class="bi bi-heart-fill"></i> 10</h2>
            <h2><i class="bi bi-chat-right-text"></i> 5</h2>
        </div>
    </div>
    </a>

    <a href="/WhatsTheCup/app/views/admin-views/Ad-Post.php">
    <div class="publicacion">
        <div class="preview">
            <h1 class="titulo">LOREM IPSUM</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
            Cras nec volutpat justo. Vestibulum at ante at dolor lacinia sollicitudin.</p>
        </div>

        <div class="interaccion">
            <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-post">
            <h2><i class="bi bi-heart-fill"></i> 10</h2>
            <h2><i class="bi bi-chat-right-text"></i> 5</h2>
        </div>
    </div>
    </a>

    <a href="/WhatsTheCup/app/views/admin-views/Ad-Post.php">
    <div class="publicacion">
        <div class="preview">
            <h1 class="titulo">LOREM IPSUM</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
            Cras nec volutpat justo. Vestibulum at ante at dolor lacinia sollicitudin.</p>
        </div>

        <div class="interaccion">
            <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-post">
            <h2><i class="bi bi-heart-fill"></i> 10</h2>
            <h2><i class="bi bi-chat-right-text"></i> 5</h2>
        </div>
    </div>
    </a>


</div> <!-- publicaciones -->


</div> <!-- pagina-principal -->
</div> <!-- layout -->



<!-- Modal de publicación  -->
<div id="modal-publicacion" class="modal" aria-hidden="true">
  <div class="modal-backdrop" id="modal-backdrop"></div>
  <div class="modal-window" role="dialog" aria-modal="true" aria-labelledby="titulo-modal">
    <button class="modal-close" id="modal-close" aria-label="Cerrar">✕</button>
    <h2 id="titulo-modal">Contribuir a Norteamérica 2026</h2>
    <hr>

    <form id="form-publicacion" action="/WhatsTheCup/index.php?route=ajax_mundial_modal" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="mundial_id" id="input-mundial-id" value="<?= $mundial['id'] ?>">

      <input type="text" id="titulo" name="titulo" placeholder="AGREGA UN TÍTULO" required>
      <textarea id="contenido" name="contenido" placeholder="Contenido de la infografía..." rows="6"></textarea>

     <div class="fila-cat">
          <select id="categoria" name="categoria_id" required>
            
                        <option value="0" selected disabled>Seleccione Categoría *</option>

                        <?php foreach ($categorias as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['id']) ?>">
                    <?= htmlspecialchars($cat['NOMBRE'] ?? $cat['nombre']) ?>
                </option>
            <?php endforeach; ?>
          </select>

        <input type="file" name="i_archivo" id="input-imagen" style="display:none">
        

        <button type="button" class="multimedia" data-tipo="imagen">
            <i class="bi bi-image"></i>
        </button>




      </div>



      <div class="modal-actions">
      <hr>
      <button type="submit" class="btn-publicar" name="btn_crear_publicacion">Publicar</button>
      </div>
    </form>
  </div>
</div>



<h1 id="footer">Whats The Cup. Todos los derechos reservados</h1>

<script src="/WhatsTheCup/public/js/Us_Infografia.js"></script>

</body>
</html>
