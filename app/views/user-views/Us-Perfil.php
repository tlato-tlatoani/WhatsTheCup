<?php

    if (!defined('BASE_URL')) {

         define('BASE_URL', '/WhatsTheCup/');
    }

    require_once __DIR__ . '/../../controllers/actualizar_usuario.php'; 

?>

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
  
<form id="form-perfil" action="/WhatsTheCup/index.php" method="POST" enctype="multipart/form-data">

  <div id="header-perfil">

   <div class="perfil-header">
    <!-- Foto de perfil -->
    <div class="upload">
        <img id="foto-de-perfil" src="<?php echo htmlspecialchars($usuario['IMAGEN_PERFIL'] ?? '/WhatsTheCup/public/imagenes/FDP.png'); ?>" alt="Foto de perfil">
        <!-- <input type="file" id="input-foto" name="IMAGEN_PERFIL" style="display:none;" disabled>
        <button type="button" id="btn-cambiar-foto">Cambiar foto</button> -->
    </div>

    <!-- Saludo / Nombre -->
    <div id="saludo">
        <h1>HOLA,</h1>
        <p id="static-nombre"><?php  echo htmlspecialchars($usuario['NOMBRES']); ?></p>
        <input type="text" name="NOMBRES" id="input-nombre" class="editar" value="<?php echo htmlspecialchars($usuario['NOMBRES']); ?>" style="display:none;" disabled>
    </div>
</div>

      <div id="div-genero">
    <label>Género:</label>
    <p id="static-nombre"><?php echo htmlspecialchars($usuario['GENERO'] ?? 'Genero'); ?></p>
    <select name="GENERO" class="editar" style="display:none;" disabled>
        <option value="F" <?php echo ($usuario['GENERO']=='F') ? 'selected' : ''; ?>>Femenino</option>
        <option value="M" <?php echo ($usuario['GENERO']=='M') ? 'selected' : ''; ?>>Masculino</option>
    </select>
</div>

<div id="div-fecha">
    <i class="bi bi-cake"></i>
        <?php $fecha_db = $usuario['NACIMIENTO'] ?? ''; ?>
        <p id="static-fecha"><?php echo !empty($fecha_db) ? date('d/m/Y', strtotime($fecha_db)) : 'Fecha no disponible'; ?></p> 
    
        <input type="date" name="NACIMIENTO" class="editar" value="<?php echo htmlspecialchars($usuario['NACIMIENTO'] ?? ''); ?>" style="display:none;" disabled>
</div>

<div id="div-pais">
    <label>País:</label>
     <p id="static-nombre"><?php echo htmlspecialchars($usuario['PAIS_ORIGEN'] ?? 'País de Origen no disponible'); ?></p>
    <input type="text" name="PAIS_ORIGEN" class="editar" value="<?php echo htmlspecialchars($usuario['PAIS_ORIGEN']); ?>" style="display:none;" disabled>
</div>

<div id="div-nacionalidad">
    <label>Nacionalidad:</label>
     <p id="static-nombre"><?php echo htmlspecialchars($usuario['NACIONALIDAD'] ?? 'Nacionalidad no disponible'); ?></p>
    <input type="text" name="NACIONALIDAD" class="editar" value="<?php echo htmlspecialchars($usuario['NACIONALIDAD']); ?>" style="display:none;" disabled>
</div>

<div id="div-correo">
    <label style="display:none;">Correo:</label>
    <input type="email" name="CORREO" class="editar" value="<?php echo htmlspecialchars($usuario['CORREO']); ?>" style="display:none;" disabled>
</div>

<div id="div-contra">
    <label style="display: none;">Contraseña</label>
    <input type="password" name="CONTRASENNA" class="editar" value="" style="display:none;" disabled>
</div>


      <div id="botones">
        <button id="btn-editar" onclick="editar()">Editar</button>
        <button id="btn-guardar" name="btn_actualizar" style="display:none;" onclick="guardar()" type="submit">Guardar</button>
    </form>
    
        <a href="/WhatsTheCup/app/views/user-views/Us-IniciarSesion.php"><button id="btn-cerrar">Cerrar sesión</button></a>
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




