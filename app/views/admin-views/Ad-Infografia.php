<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Ad_Infografia.css">
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
                <h1> NORTEAMÉRICA 2026</h1>
                <button id="btn-contribuir">Contribuir</button>
            </div>
   
            <div id="header-intro">
                <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-post">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras nec volutpat justo. 
                Vestibulum at ante at dolor lacinia sollicitudin.In condimentum laoreet orci luctus tristique. 
                Aliquam erat volutpat. Pellentesque sed ipsum aliquam, feugiat erat et, aliquet eros. </p>

            </div>
    
     </div>

<div id="publicaciones">

    <a href="/WhatsTheCup/app/views/admin-views/Ad-Post.php">
    <div class="publicacion">
        

           <div class="preview">
                <h1 class="titulo">LOREM IPSUM</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras nec volutpat justo. 
                 Vestibulum at ante at dolor lacinia sollicitudin.In condimentum laoreet orci luctus tristique. 
                Aliquam erat volutpat. Pellentesque sed ipsum aliquam, feugiat erat et, aliquet eros. </p>
            </div>
                

        <div class="interaccion">
            <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-post">
            <h2> <i class="bi bi-heart-fill"></i> 10</h2>
            <h2> <i class="bi bi-chat-right-text"></i> 5</h2>
        </div>

    </div>
    </a>

    <a href="/WhatsTheCup/app/views/admin-views/Ad-Post.php">
    <div class="publicacion">
        

           <div class="preview">
                <h1 class="titulo">LOREM IPSUM</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras nec volutpat justo. 
                 Vestibulum at ante at dolor lacinia sollicitudin.In condimentum laoreet orci luctus tristique. 
                Aliquam erat volutpat. Pellentesque sed ipsum aliquam, feugiat erat et, aliquet eros. </p>
            </div>
                

        <div class="interaccion">
            <img src="/WhatsTheCup/app/views/admin-views/Ad-Post.php" class="imagen-post">
            <h2> <i class="bi bi-heart-fill"></i> 10</h2>
            <h2> <i class="bi bi-chat-right-text"></i> 5</h2>
        </div>

    </div>
    </a>

    <a href="/WhatsTheCup/app/views/admin-views/Ad-Post.php">
    <div class="publicacion">
        

           <div class="preview" style="text-decoration: none;">
                <h1 class="titulo">LOREM IPSUM</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras nec volutpat justo. 
                 Vestibulum at ante at dolor lacinia sollicitudin.In condimentum laoreet orci luctus tristique. 
                Aliquam erat volutpat. Pellentesque sed ipsum aliquam, feugiat erat et, aliquet eros. </p>
            </div>
                

        <div class="interaccion">
            <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-post">
            <h2> <i class="bi bi-heart-fill"></i> 10</h2>
            <h2> <i class="bi bi-chat-right-text"></i> 5</h2>
        </div>

    </div>
    </a>
   

</div>

</div>
</div>


<h1 id="footer"> Whats The Cup. Todos los derechos reservados </h1>
 

<!-- Modal de publicación  -->
<div id="modal-publicacion" class="modal" aria-hidden="true">
  <div class="modal-backdrop" id="modal-backdrop"></div>
  <div class="modal-window" role="dialog" aria-modal="true" aria-labelledby="titulo-modal">
    <button class="modal-close" id="modal-close" aria-label="Cerrar">✕</button>
    <h2 id="titulo-modal">Contribuir a Norteamérica 2026</h2>
    <hr>

    <form id="form-publicacion">
      <input type="text" id="titulo" name="titulo" placeholder="AGREGA UN TÍTULO" required>
      <textarea id="contenido" name="contenido" placeholder="Contenido de la infografía..." rows="6"></textarea>

     <div class="fila-cat">
          <select id="categoria" name="categoria">
            <option value="">Categoría</option>
            <option value="noticia">1</option>
            <option value="estadistica">3</option>
            <option value="opinion">3</option>
          </select>

          <button class="multimedia"><i class="bi bi-camera-reels"></i></button>
          <button class="multimedia"><i class="bi bi-image"></i></button>
      </div>


      <div class="modal-actions">
      <hr>
      <button type="submit" class="btn-publicar">Publicar</button>
      </div>
    </form>
  </div>
</div>

<script src="/WhatsTheCup/public/js/Header.js"></script>
<script src="/WhatsTheCup/public/js/Ad_Infografia.js"></script>

</body>
</html>