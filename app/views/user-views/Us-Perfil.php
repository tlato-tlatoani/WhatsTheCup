<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pantalla Perfil</title>
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Us_Perfil.css">
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Header.css">
  <link rel="stylesheet" href="/WhatsTheCup/public/css/Fuentes.css">
</head>
<body>
  
  <div id="header-perfil">
    <img id="foto-de-perfil" src="/WhatsTheCup/public/imagenes/FDP.png" alt="Foto de perfil">
    <div id="saludo">
      <h1>HOLA,</h1>
      <h1>NOMBRE DE USUARIO</h1>
    </div>



    <form id="form-perfil">

      <div id="div-genero">
        <label>Género:</label>
        <p id="static-genero">Femenino</p>
        <input type=text  class="editar" style="display:none;"> 
      </div>

      <div id="div-fecha">
        <i class="bi bi-cake"></i>
        <p id="static-fecha">01/01/2000</p>
        <input type=date  class="editar" style="display:none;">
      </div>

     <div id="div-pais">
          <label>País:</label>
          <p id="static-pais">México</p>
          <input type=text  class="editar" style="display:none;">
     </div>

      <div id="div-nacionalidad">
        <label>Nacionalidad:</label>
        <p id="static-nacionalidad">Mexicana</p>
        <input type=text  class="editar" style="display:none;">
      </div>

      <div id="div-correo">
        <label style="display:none;">Correo:</label>
        <input type="mail"  class="editar" style="display:none;">
      </div>
     
      <div id="div-contra">
        <label style="display: none;">Contraseña</label>
        <input type="password"  class="editar" style="display:none;">
      </div>

      <div id="botones">
        <button id="btn-editar" onclick="editar()">Editar</button>
        <button id="btn-guardar" style="display:none;" onclick="guardar()" type="submit">Guardar</button>
    </form>
    
        <a href="/WhatsTheCup/app/views/user-views/Us-Busqueda.php"><button id="btn-cerrar">Cerrar sesión</button></a>
  </div>

    </div>
  

  <a href="/WhatsTheCup/app/views/user-views/Us-Post.php">
    <div class="publicacion">
        

           <div class="preview">
                <h1 class="titulo">LOREM IPSUM</h1>
                <h2 class="infografia"> en Norteamérica 2026</h2>
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

    <a href="/WhatsTheCup/app/views/user-views/Us-Post.php">
    <div class="publicacion">
        

           <div class="preview">
                <h1 class="titulo">LOREM IPSUM</h1>
                <h2 class="infografia"> en Norteamérica 2026</h2>
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

    <a href="/WhatsTheCup/app/views/user-views/Us-Post.php">
    <div class="publicacion">
        

           <div class="preview" style="text-decoration: none;">
                <h1 class="titulo">LOREM IPSUM</h1>
                <h2 class="infografia"> en Norteamérica 2026</h2>
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


<script src="/WhatsTheCup/public/js/Header.js"></script>
</body>
</html>
