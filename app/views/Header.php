<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Usamos BASE_URL que definiste en index.php: define('BASE_URL', '/WhatsTheCup/');
$baseIndex = '/WhatsTheCup/index.php';

// Determinar el tipo de usuario y la URL de inicio (Home)
$esAdmin = !empty($_SESSION['tipo_usuario']) && strtoupper($_SESSION['tipo_usuario']) === 'ADMIN';

if ($esAdmin) {
    $homeUrl = $baseIndex . '?route=adlanding';
} elseif (!empty($_SESSION['tipo_usuario'])) {
    $homeUrl = $baseIndex . '?route=landing';
} else {
    $homeUrl = $baseIndex . '?route=landing';
}

// [NUEVO] Definir la URL a la que se enviará la búsqueda (Controller/Router)
$searchActionUrl = $baseIndex; // Enviamos al index para que el router maneje la ruta.
?>

<link rel="stylesheet" href="/WhatsTheCup/public/css/fuentes.css">
<link rel="stylesheet" href="/WhatsTheCup/public/node_modules/bootstrap-icons/font/bootstrap-icons.css">

<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= htmlspecialchars($homeUrl) ?>">WHATS THE CUP</a>

        <form class="d-flex" role="search" id="search-form" action="<?= htmlspecialchars($searchActionUrl) ?>" method="GET">
            <input type="hidden" name="route" value="us_busqueda">
            
            <input class="form-control" type="search" name="q" placeholder="Buscar..." aria-label="Search" required/>
            
            <button class="btn btn-outline-success" type="submit" id="search-button" name="btn_buscar">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('search-form');
        const searchButton = document.getElementById('search-button');
        
        // Determinar si el usuario actual es administrador (basado en la sesión PHP)
        // Usamos una variable JavaScript generada por PHP para esto
        const esAdmin = <?= json_encode($esAdmin) ?>;

        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                if (esAdmin) {
                    e.preventDefault(); // Detener el envío del formulario
                    
                    const confirmacion = confirm("Estás a punto de salir de las vistas del administrador. ¿Deseas continuar con la búsqueda?");
                    
                    if (confirmacion) {
                        // Si el admin confirma, reanudar el envío del formulario
                        searchForm.submit();
                    } 
                    // Si el admin cancela, no hacemos nada (el envío ya fue detenido).
                }
                // Si NO es admin, el envío (submit) se ejecuta normalmente.
            });
        }
    });
</script>