<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Ad_AgregarMundial.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/SidebarAdmin.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Header.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Fuentes.css">
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

    <title>Whats The Cup</title>
</head>
<body>

<div id="layout">
<div id="sidebar-container"></div>
<div id="pagina-principal">

     <div id="introduccion">
            <div id="header-titulo">
                <h1>AGREGAR MUNDIAL</h1>
            </div>
     </div>


   <form id="form-mundial">
  <div class="label-input mundial">
    <label>País</label>
    <input type="text" name="i_pais" class="mundial-titulo">
  </div>

  <div class="label-input anio">
    <label>Año</label>
    <input type="text" name="i_anio" class="mundial-titulo">
  </div>

  <div class="imagen-icono">
    <label>Agregar ícono</label>
    <input type="file" name="i_imagen" id="archivo-icono">
    <button type="button" class="multimedia" onclick="document.getElementById('archivo-icono').click()">
     <i class="bi bi-image"></i>
    </button>


    <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-mundial">
  </div>

  <div class="label-input descripcion">
    <label>Descripción</label>
    <textarea name="i_descripcion" rows="5"></textarea>
  </div>

  <div class="label-input campeon">
    <label>Campeón</label>
    <input type="text" name="i_campeon">
  </div>

  <div class="label-input marcador">
    <label>Marcador</label>
    <input type="text" name="i_marcador">
  </div>

  <div class="label-input subcampeon">
    <label>Subcampeón</label>
    <input type="text" name="i_subcampeon">
  </div>

  <div class="label-input goleador">
    <label>Líder de goleo</label>
    <input type="text" name="i_goleador">
  </div>

  <div class="label-input cantante">
    <label>Cantante</label>
    <input type="text" name="i_cantante">
  </div>

  <div class="label-input equipos">
    <label>Equipos</label>
    <input name="basic" value="tag1, tag2">
  </div>

  <div class="copa">
    <label>Copa</label>
    <input type="file" name="i_copa" id="archivo-copa">
     <button type="button" class="multimedia" onclick="document.getElementById('archivo-copa').click()">
     <i class="bi bi-image"></i>
    </button>
    <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-mundial">
  </div>

  
  <div class="mascota">
    <label>Mascota</label>
    <input type="file" name="i_mascota" id="archivo-mascota">
    <button type="button" class="multimedia" onclick="document.getElementById('archivo-mascota').click()">
     <i class="bi bi-image"></i>
    </button>
    <img src="/WhatsTheCup/public/imagenes/FDP.png" class="imagen-mundial">
  </div>


  <button type="submit" id="btn-publicar">Agregar</button>
</form>


</div>

</div>
</div>



<h1 id="footer"> Whats The Cup. Todos los derechos reservados </h1>
    

  

<script src="/WhatsTheCup/public/js/Header.js"></script>
<script src="/WhatsTheCup/public/js/Ad_AgregarMundial.js"></script>

</body>
</html>