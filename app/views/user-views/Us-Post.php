<?php
require_once PROJECT_ROOT . '/app/controllers/ver_publicacion.php';
$likes_actuales = $likes_actuales ?? 0;

if ($error_publicacion) {
    echo "<h2 style='color:red'>$error_publicacion</h2>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/SidebarUsuario.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Us_Post.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Header.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Fuentes.css">
    <title>Whats The Cup</title>
</head>
<body>


<div id="layout">
    <div id="sidebar-container"></div>

    <div id="layout-inside">
    <div id="publicacion-contenido">

    <div id="titulo">
        <h1><?= htmlspecialchars($publicacion['titulo']) ?></h1>
        
        <h2>
    <?= htmlspecialchars($publicacion['nombre_autor_completo']) ?> | 
    <?= date("d \d\e F \d\e Y", strtotime($publicacion['fecha_publicacion'])) ?>
      </h2>

    </div>

   <p><?= nl2br(htmlspecialchars($publicacion['descripcion'])) ?></p>


    <?php if (!empty($publicacion['base64_multimedia'])): ?>
    <img id="imagen-publicacion"
         src="data:<?= $publicacion['tipo_mime'] ?>;base64,<?= $publicacion['base64_multimedia'] ?>">
<?php endif; ?>

<h2 id="categoria"><?= htmlspecialchars($publicacion['nombre_categoria']) ?></h2>

    </div>

  <div class="likes">
            <button type="button" class="btn-like" data-id="<?= $publicacion['id'] ?>">
                <i class="bi bi-heart-fill"></i>
                <span class="like-count"><?= $likes_actuales ?></span>
            </button>
        </div>


    <hr>


    <div id="comentarios">
    <div id="escribir-comentario">
        <img src="/WhatsTheCup/public/imagenes/FDP.png" class="foto-comentario">
        <input type="text" placeholder="Añade un comentario...">
    </div>
    
   <div class="comentario">
  <img src="/WhatsTheCup/public/imagenes/FDP.png" class="foto-comentario">
  <div class="comentario-contenido">
    <h3>Usuario X</h3>
    <p class="comentario-usuario">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras nec volutpat justo. Vestibulum at ante at dolor lacinia sollicitudin. In condimentum laoreet orci luctus tristique.</p>
  </div>
</div>

<div class="comentario">
  <img src="/WhatsTheCup/public/imagenes/FDP.png" class="foto-comentario">
  <div class="comentario-contenido">
    <h3>Usuario X</h3>
    <p class="comentario-usuario">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras nec volutpat justo. Vestibulum at ante at dolor lacinia sollicitudin. In condimentum laoreet orci luctus tristique.</p>
  </div>
</div>

    </div>

    </div>


</div>

<h1 id="footer"> Whats The Cup. Todos los derechos reservados </h1>
    
<script src="/WhatsTheCup/public/js/Header.js"></script>
<script src="/WhatsTheCup/public/js/Us_Post.js"></script>
<script>
// JS para manejar el click de likes
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('.btn-like').forEach(btn => {
    btn.addEventListener('click', async () => {
      const publicacionId = btn.dataset.id;

      try {
        const response = await fetch('/WhatsTheCup/index.php?route=like_post', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ publicacion_id: publicacionId })
        });

        const data = await response.json();

        if (data.success) {
          // Actualizar contador
          btn.querySelector('.like-count').textContent = data.likes;
          
          // Cambiar clase del botón según like/deslike
          if (data.liked) {
            btn.classList.add('liked'); // por ejemplo, cambiar color
          } else {
            btn.classList.remove('liked');
          }

        } else {
          console.error("No se pudo registrar el like:", data.message);
        }

      } catch (error) {
        console.error("Error de red al dar like:", error);
      }
    });
  });
});


</script>
</body>
</html>