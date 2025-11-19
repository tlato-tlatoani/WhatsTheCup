<?php
require_once dirname(__DIR__, 3) . '/app/models/Model_Mundial.php';
require_once dirname(__DIR__, 3) . '/app/models/Model_Categorias.php';

require_once PROJECT_ROOT . '/app/controllers/publicaciones_por_infografia.php';


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

    <link rel="stylesheet" href="/WhatsTheCup/public/css/Ad_Infografia.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/SidebarAdmin.css">
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

            <?php 
            if (!empty($error_consulta) && empty($publicaciones_infografia)): ?>
                <div class="message-info" style="color: red; padding: 10px; border: 1px solid red; background-color: #f8d7da;">
                    <?php echo htmlspecialchars($error_consulta); ?>
                </div>
                
            <?php else: ?>
                
                <?php foreach ($publicaciones_infografia as $post): ?>
                    
                    <div class="publicacion">
                        
                        <div class="preview">
                            <div class="nombre-post">
                                <h1 class="titulo"><?php echo htmlspecialchars($post['titulo']); ?></h1>
                                <h2 class="infografia">
                                    Categoría: <?php echo htmlspecialchars($post['nombre_categoria']); ?> | Autor: <?php echo htmlspecialchars($post['nombre_autor_completo']); ?>
                                    | Fecha: <?php echo htmlspecialchars(date('d/m/Y', strtotime($post['fecha_publicacion']))); ?>
                                </h2>
                            </div>

                            <p><?php echo nl2br(htmlspecialchars($post['descripcion'])); ?></p>
                        </div>
                            
                        <div class="multimedia">
                            <?php if (!empty($post['base64_multimedia'])): ?>
                                <img src="data:<?= $post['tipo_mime'] ?>;base64,<?= $post['base64_multimedia'] ?>" 
                                    class="imagen-post">
                            <?php else: ?>
                                <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-post">
                            <?php endif; ?>
                            
                            <a href="<?= BASE_URL ?>index.php?route=ver_publicacion&id=<?= htmlspecialchars($post['id']) ?>" class="btn-ver-post">
                                Ver Publicación Completa <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                    
                <?php endforeach; ?>
                <?php endif; ?>

        </div>



</div> <!-- pagina-principal -->
</div> <!-- layout -->

<h1 id="footer">Whats The Cup. Todos los derechos reservados</h1>

<script src="/WhatsTheCup/public/

</div> <!-- pagina-principal -->
</div> <!-- layout -->

<h1 id="footer">Whats The Cup. Todos los derechos reservados</h1>

<script src="/WhatsTheCup/public/js/Header.js"></script>
<script src="/WhatsTheCup/public/js/Ad_Infografia.js"></script>

</body>
</html>
