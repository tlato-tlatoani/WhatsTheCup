<?php

require_once PROJECT_ROOT . '/app/controllers/publicaciones_pendientes.php';

// Este archivo asume que el controlador 'publicaciones_por_aprobar.php' 
// ya se ha ejecutado y ha poblado la variable $publicaciones_pendientes.

// Fallback si la vista se accede directamente sin el controlador
if (!isset($publicaciones_pendientes)) {
    // Si la constante PROJECT_ROOT existe, intentamos cargar el controlador
    if (defined('PROJECT_ROOT')) {
        require_once PROJECT_ROOT . '/app/controllers/publicaciones_pendientes.php';
    } else {
        // Si no se puede cargar el controlador, inicializamos a vacío
        $publicaciones_pendientes = [];
        $error_consulta = "Error: La aplicación no está cargando las publicaciones correctamente.";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Ad_AprobarPosts.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/SidebarAdmin.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Header.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Fuentes.css">
    <title>Whats The Cup</title>
</head>
<body>

<div id="layout">
<div id="sidebar-container"></div>
<div id="pagina-principal">
     <div id="introduccion">
            <div id="header-titulo">
                <h1> PUBLICACIONES POR APROBAR </h1>
            
            </div>
   
    
     </div>

<div id="publicaciones">


<!-- Muestra mensaje de error o publicaciones -->
    <?php if (!empty($error_consulta)): ?>
        <div class="message-error" style="color: red; padding: 10px; border: 1px solid red;">
            <?php echo htmlspecialchars($error_consulta); ?>
        </div>
    <?php elseif (empty($publicaciones_pendientes)): ?>
        <div class="message-info" style="color: #007bff; padding: 10px; border: 1px solid #007bff; background-color: #e6f3ff;">
            No hay publicaciones pendientes de aprobación.
        </div>
    <?php else: ?>
        

       

<!-- INICIO DEL BUCLE DE PUBLICACIONES PENDIENTES -->
        <?php foreach ($publicaciones_pendientes as $post): ?>
            
            <div class="publicacion">
                
                <div class="preview">
                    <div class="nombre-post">
                        <!-- ID y Título de la Publicación -->
                        <h1 class="titulo">#<?php echo htmlspecialchars($post['id']) . ' - ' . htmlspecialchars($post['titulo']); ?></h1>
                        <!-- Categoría y Autor -->
                        <h2 class="infografia">Categoría: <?php echo htmlspecialchars($post['nombre_categoria']); ?> | Autor: <?php echo htmlspecialchars($post['nombre_autor_completo']); ?></h2>
                    </div>

                    <!-- Descripción de la Publicación -->
                    <p><?php echo nl2br(htmlspecialchars($post['descripcion'])); ?></p>
                </div>
                    

                <div class="interaccion">
                    <!-- Nota: La imagen/multimedia (MULTIMEDIA) requiere un endpoint PHP para servir el BLOB -->
                    <!-- Por ahora, usamos un placeholder o la imagen predefinida en tu HTML -->
                    <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-post" alt="Preview Multimedia">
                    
                    <!-- Botones de Acción (Formulario para Aprobación/Rechazo) -->

                    <!-- Botón APROBAR -->
               <form method="POST" action="<?= BASE_URL ?>index.php?route=aprobar_post" style="display: inline;">
                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                    <input type="hidden" name="accion" value="aprobar">
                    <button type="submit" class="btn-aprobar" title="Aprobar publicación">
                        <i class="bi bi-check-lg"></i>
                    </button>
                </form>

                    
                    <!-- Botón RECHAZAR -->
                    <form method="POST" action="<?= BASE_URL ?>index.php?route=rechazar_post" style="display: inline;">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                        <input type="hidden" name="accion" value="rechazar">
                        <button type="submit" class="btn-rechazar" title="Rechazar publicación">
                            <i class="bi bi-x"></i>
                        </button>
                    </form>

                </div>

            </div>
            
        <?php endforeach; ?>
        <!-- FIN DEL BUCLE -->

    <?php endif; ?>

    



</div>

</div>
</div>


 
<h1 id="footer"> Whats The Cup. Todos los derechos reservados </h1>
    



<script src="/WhatsTheCup/public/js/Header.js"></script>
<script src="/WhatsTheCup/public/js/Ad_AprobarPosts.js"></script>

</body>
</html>