<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Fugaz+One&display=swap" rel="stylesheet">
  <title>Dashboard - What's The Cup</title>
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Ad_Landing.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/SidebarAdmin.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Header.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Fuentes.css">
</head>
<body>

<div id="layout">
   <div id="sidebar-container"></div>

  <div id="pagina-principal">

    <div id="header-mundiales">
      <h1>LISTA DE MUNDIALES</h1>
      <select placeholder="Filtrar por">

        <option>Más reciente</option>
        <option> Más antiguo</option>
        <option> Más popular</option>

      </select>

    </div>

    <div id="mundiales">
      <a href="/WhatsTheCup/app/views/admin-views/Ad-Infografia.php">
        <div class="card" style="width: 18rem;">
          <img src="/WhatsTheCup/public/imagenes/FDP.png" class="card-img-top" alt="...">
          <div class="card-body">
            <p class="card-text">Norteamérica 2026</p>
          </div>
       </div>
      </a>

      <a href="/WhatsTheCup/app/views/admin-views/Ad-Infografia.php">
        <div class="card" style="width: 18rem;">
          <img src="/WhatsTheCup/public/imagenes/FDP.png" class="card-img-top" alt="...">
          <div class="card-body">
            <p class="card-text">Norteamérica 2026</p>
          </div>
       </div>
      </a>

      <a href="/WhatsTheCup/app/views/admin-views/Ad-Infografia.php">
        <div class="card" style="width: 18rem;">
          <img src="/WhatsTheCup/public/imagenes/FDP.png" class="card-img-top" alt="...">
          <div class="card-body">
            <p class="card-text">Norteamérica 2026</p>
          </div>
       </div>
      </a>

      <a href="/WhatsTheCup/app/views/admin-views/Ad-Infografia.php">
        <div class="card" style="width: 18rem;">
          <img src="/WhatsTheCup/public/imagenes/FDP.png" class="card-img-top" alt="...">
          <div class="card-body">
            <p class="card-text">Norteamérica 2026</p>
          </div>
       </div>
      </a>

      <a href="/WhatsTheCup/app/views/admin-views/Ad-Infografia.php">
        <div class="card" style="width: 18rem;">
          <img src="/WhatsTheCup/public/imagenes/FDP.png" class="card-img-top" alt="...">
          <div class="card-body">
            <p class="card-text">Norteamérica 2026</p>
          </div>
       </div>
      </a>

      <a href="/WhatsTheCup/app/views/admin-views/Ad-Infografia.php">
        <div class="card" style="width: 18rem;">
          <img src="/WhatsTheCup/public/imagenes/FDP.png" class="card-img-top" alt="...">
          <div class="card-body">
            <p class="card-text">Norteamérica 2026</p>
          </div>
       </div>
      </a>

      <a href="/WhatsTheCup/app/views/admin-views/Ad-Infografia.php">
        <div class="card" style="width: 18rem;">
          <img src="/WhatsTheCup/public/imagenes/FDP.png" class="card-img-top" alt="...">
          <div class="card-body">
            <p class="card-text">Norteamérica 2026</p>
          </div>
       </div>
      </a>

      <a href="/WhatsTheCup/app/views/admin-views/Ad-Infografia.php">
        <div class="card" style="width: 18rem;">
          <img src="/WhatsTheCup/public/imagenes/FDP.png" class="card-img-top" alt="...">
          <div class="card-body">
            <p class="card-text">Norteamérica 2026</p>
          </div>
       </div>
      </a>

      

      <div id="paginacion">
      
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

      </div>


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
<script src="/WhatsTheCup/public/js/Ad_Landing.js"></script>
</body>
</html>
