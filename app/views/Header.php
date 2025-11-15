<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Usamos BASE_URL que definiste en index.php: define('BASE_URL', '/WhatsTheCup/');
$baseIndex = '/WhatsTheCup/index.php';

// Rutas según tu index.php (case 'landing' y case 'adlanding')
$adminLanding  = $baseIndex . '?route=adlanding'; // Landing admin
$userLanding   = $baseIndex . '?route=landing';   // Landing usuario normal
$guestLanding  = $baseIndex . '?route=landing';   // Invitado (no logueado)

// Decidimos a dónde apunta el título según el tipo de usuario
if (!empty($_SESSION['tipo_usuario']) && strtoupper($_SESSION['tipo_usuario']) === 'ADMIN') {
    // Admin logueado
    $homeUrl = $adminLanding;
} elseif (!empty($_SESSION['tipo_usuario'])) {
    // Cualquier usuario logueado que NO sea ADMIN (por ejemplo 'USER')
    $homeUrl = $userLanding;
} else {
    // Nadie logueado
    $homeUrl = $guestLanding;
}
?>

<link rel="stylesheet" href="/WhatsTheCup/public/css/fuentes.css">
<link rel="stylesheet" href="/WhatsTheCup/public/node_modules/bootstrap-icons/font/bootstrap-icons.css">

<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= htmlspecialchars($homeUrl) ?>">WHATS THE CUP</a>

        <form class="d-flex" role="search">
            <input class="form-control" type="search" placeholder="Buscar..." aria-label="Search"/>
            <button class="btn btn-outline-success" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</nav>
