<?php
require_once dirname(__DIR__, 3) . '/app/models/Model_Categorias.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: /WhatsTheCup/index.php?route=iniciarsesion");
    exit;
}

$model = new Model_Categorias();
$categorias = $model->obtenerCategorias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - What's The Cup</title>

    <link rel="stylesheet" href="/WhatsTheCup/public/css/Ad_Categorias.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/SidebarAdmin.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Header.css">
    <link rel="stylesheet" href="/WhatsTheCup/public/css/Fuentes.css">
</head>

<body>

<main class="main-container">

    <!-- SIDEBAR -->
   <div id="sidebar-container">
    <?php include dirname(__DIR__) . '/SidebarAdmin.php'; ?>
</div>


    <!-- CONTENIDO PRINCIPAL -->
    <section class="categories-section">

        <h2>CATEGORÍAS</h2>

        <!-- GRID DINÁMICO DE CATEGORÍAS -->
        <div class="categories-grid">

            <?php if (empty($categorias)): ?>
                <p style="color:#fff; font-size:18px;">No hay categorías registradas.</p>
            <?php else: ?>
                <?php foreach ($categorias as $cat): ?>
                    <div class="category-box">
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>

        <!-- FORMULARIO PARA AGREGAR CATEGORÍA -->
        <div class="add-category">
            <h3>AGREGAR CATEGORIA</h3>

            <form method="POST" action="/WhatsTheCup/index.php">
                <input type="hidden" name="route" value="adcategorias">

                <input type="text" name="nombre_categoria"
                       placeholder="Nombre de la categoría" required>

                <button type="submit" name="btn_agregar_categoria">AGREGAR</button>
            </form>

            <?php if (isset($_GET['ok'])): ?>
                <p class="success-msg">✔ Categoría agregada correctamente</p>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <p class="error-msg">✖ Ocurrió un error al agregar la categoría</p>
            <?php endif; ?>

        </div>

    </section>

</main>

<script src="/WhatsTheCup/public/js/Header.js"></script>
<script src="/WhatsTheCup/public/js/Categorias.js"></script>

</body>
</html>
