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
        <form id="form-agregar-comentario">
            <input type="hidden" name="publicacion_id" value="<?= htmlspecialchars($publicacion['id']) ?>">

            <input type="text" 
                   name="contenido" 
                   id="input-comentario" 
                   placeholder="Añade un comentario..."
                   required>
            
            <button type="submit" style="display: none;"></button>
        </form>
    </div>
    
   <div class="comentario">
 <?php 
    // Aseguramos que la variable exista si el controlador no la seteo
    $comentarios_activos = $comentarios_activos ?? []; 
    
    if (!empty($comentarios_activos)): ?>
        <?php foreach ($comentarios_activos as $comentario): ?>
            <div class="comentario">
              <img src="/WhatsTheCup/public/imagenes/FDP.png" class="foto-comentario">
              <div class="comentario-contenido">
                <h3><?= htmlspecialchars($comentario['nombre_usuario']) ?></h3>
                <p class="comentario-usuario"><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>
              </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; color: gray;">Sé el primero en comentar.</p>
    <?php endif; ?>
    </div>

    
</div>


    </div>

    </div>


</div>

<h1 id="footer"> Whats The Cup. Todos los derechos reservados </h1>
    
<script src="/WhatsTheCup/public/js/Header.js"></script>
<script src="/WhatsTheCup/public/js/Us_Post.js"></script>
<script>
// JS para manejar el click de likes y el envío de comentarios
document.addEventListener("DOMContentLoaded", () => {

    // ==========================================================
    // 1. LÓGICA DE LIKES 
    // ==========================================================
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

    // ==========================================================
    // 2. LÓGICA DE COMENTARIOS (FALTABA E IMPLEMENTADA)
    // ==========================================================
    const formComentario = document.getElementById('form-agregar-comentario');
    const inputComentario = document.getElementById('input-comentario');

    if (formComentario) {
        // Intercepta el evento SUBMIT (se dispara al presionar Enter)
        formComentario.addEventListener('submit', async (e) => {
            e.preventDefault(); // Detiene el envío de formulario tradicional

            const contenido = inputComentario.value.trim();
            // Obtiene el ID de la publicación del input oculto
            const publicacionId = formComentario.querySelector('input[name="publicacion_id"]').value; 

            if (contenido === '') {
                return; // No enviar si está vacío
            }

            const dataToSend = {
                publicacion_id: publicacionId,
                contenido: contenido
            };

            try {
                const response = await fetch('/WhatsTheCup/index.php?route=agregar_comentario', {
                    method: 'POST',
                    // Enviar datos como JSON
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(dataToSend) 
                });

                const data = await response.json();

                if (data.success) {
                    console.log("Comentario enviado con éxito.");
                    inputComentario.value = ''; // Limpia el input
                    alert("¡Comentario publicado con éxito!"); 
                    // [PENDIENTE]: Lógica para actualizar la lista de comentarios sin recargar.

                } else {
                    console.error("Error al enviar comentario:", data.message);
                    alert("Error al enviar comentario: " + data.message);
                }

            } catch (error) {
                console.error("Error de red al enviar comentario:", error);
            }
        });
    }

// Función auxiliar para construir el HTML del comentario.
// Usaremos la plantilla HTML de tu vista Us-Post.php.
function buildComentarioHTML(comentarioData) {
    // 1. Formatear la fecha (opcional, pero mejora la vista)
    const fecha = new Date(comentarioData.fecha_creacion).toLocaleDateString('es-ES', { 
        day: 'numeric', month: 'long', year: 'numeric' 
    });

    // 2. Devolver la estructura HTML
    return `
        <div class="comentario">
            <img src="/WhatsTheCup/public/imagenes/FDP.png" class="foto-comentario" alt="Foto de perfil">
            <div class="comentario-contenido">
                <h3>${comentarioData.nombre_usuario}</h3>
                <p class="comentario-usuario">${comentarioData.contenido}</p>
                <small class="comentario-fecha">${fecha}</small>
            </div>
        </div>
    `;
}



});
    
    // ==========================================================
</script>
</body>
</html>