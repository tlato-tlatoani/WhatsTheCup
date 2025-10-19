<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuarios - What's The Cup</title>
  <link rel="stylesheet" href="css/busqueda.css">
   <link rel="stylesheet" href="css/Header.css">
    <link rel="stylesheet" href="css/fuentes.css">
</head>
<body>



  <main class="main-container">
    <!-- Left profile card -->
    <aside class="profile-card">
      <div class="profile-pic-circle">
        <img src="imagenes/FDP.png" alt="Foto de perfil" class="profile-pic">
      </div>
      <h3>Nombre Usuario</h3>

      <!-- Botones principales -->
      <div class="buttons">
        <a href="perfil.html">  <button>Ver mi perfil</button> </a>
      </div>

      <!-- Filtro por fechas -->
      <div class="filter-section">
        <h4>Buscar por fecha</h4>
        <label for="fecha-desde">Desde:</label>
        <input type="date" id="fecha-desde">
        <label for="fecha-hasta">Hasta:</label>
        <input type="date" id="fecha-hasta">
        <button id="filtro">Filtrar</button>
      </div>

      <!-- Otros filtros -->
      <div class="extra-buttons">
        <button>Categoría</button>
        <button>Buscar usuario</button>
        <button>Cerrar sesión</button>
      </div>
    </aside>

    <!-- Right content (vacío por ahora) -->
    <section class="content-section">
      <!-- Aquí podrás poner cards, categorías o tablas después -->
    </section>
  </main>

  <script src="js/Header.js"></script>
</body>
</html>
