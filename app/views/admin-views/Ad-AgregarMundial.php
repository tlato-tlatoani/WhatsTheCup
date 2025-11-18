<?php 
if (!defined('BASE_URL')) {
    define('BASE_URL', '/WhatsTheCup/');
}
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/Ad_AgregarMundial.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/SidebarAdmin.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/Header.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/Fuentes.css">

    <!-- Tagify -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet"/>

    <title>Whats The Cup</title>
</head>
<body>

<div id="layout">

    <div id="sidebar-container">
        <?php include dirname(__DIR__) . '/SidebarAdmin.php'; ?>
    </div>

    <div id="pagina-principal">

        <div id="introduccion">
            <div id="header-titulo">
                <h1>AGREGAR MUNDIAL</h1>
            </div>
        </div>

        <!-- ===========================
             FORMULARIO COMPLETO
        ============================ -->
        
<form id="form-mundial" method="POST" enctype="multipart/form-data"
      action="<?= BASE_URL ?>index.php?route=adagregarmundial">

            <!-- TÍTULO -->
            <div class="label-input titulo">
                <label>Título del Mundial</label>
                <input type="text" name="i_titulo" class="mundial-titulo" required>
            </div>

            <!-- PAÍS -->
            <div class="label-input mundial">
                <label>País Sede</label>
                <input type="text" name="i_pais" class="mundial-titulo" required>
            </div>

            <!-- AÑO -->
            <div class="label-input anio">
                <label>Año</label>
                <input type="text" name="i_anio" class="mundial-titulo" required>
            </div>

            <!-- ICONO -->
            <div class="imagen-icono">
                <label>Ícono</label>

               <!-- <input type="file" name="i_imagen" id="archivo-icono" accept="image/*" required style="display:none;">-->

                <input type="file" name="i_imagen" id="archivo-icono" accept="image/*" required style="opacity:0; position:absolute; width:0; height:0;">


                <button type="button" class="multimedia" onclick="document.getElementById('archivo-icono').click()">
                    <i class="bi bi-image"></i>
                </button>

                <img src="<?= BASE_URL ?>public/imagenes/FDP.png" class="imagen-mundial" id="preview-icono">
            </div>

            <!-- DESCRIPCIÓN -->
            <div class="label-input descripcion">
                <label>Descripción</label>
                <textarea name="i_descripcion" rows="5" required></textarea>
            </div>

            <!-- DETALLES -->
            <div class="label-input campeon">
                <label>Campeón</label>
                <input type="text" name="i_campeon" required>
            </div>

            <div class="label-input marcador">
                <label>Marcador</label>
                <input type="text" name="i_marcador" required>
            </div>

            <div class="label-input subcampeon">
                <label>Subcampeón</label>
                <input type="text" name="i_subcampeon" required>
            </div>

            <div class="label-input goleador">
                <label>Líder de goleo</label>
                <input type="text" name="i_goleador" required>
            </div>

            <div class="label-input cantante">
                <label>Cantante del mundial</label>
                <input type="text" name="i_cantante" required>
            </div>

            <!-- EQUIPOS (TAGIFY) -->
            <div class="label-input equipos">
                <label>Equipos</label>
                <input id="input-equipos" name="input_equipos">
            </div>

            <!-- Campo hidden JSON -->
            <input type="hidden" name="equipos_json" id="equipos_json">

            <!-- COPA -->
            <div class="copa">
                <label>Copa</label>

                <!--<input type="file" name="i_copa" id="archivo-copa" accept="image/*" required style="display:none;">-->

                <input type="file" name="i_copa" id="archivo-copa" accept="image/*" required style="opacity:0; position:absolute; width:0; height:0;">


                <button type="button" class="multimedia" onclick="document.getElementById('archivo-copa').click()">
                    <i class="bi bi-image"></i>
                </button>

                <img src="<?= BASE_URL ?>public/imagenes/FDP.png" class="imagen-mundial" id="preview-copa">
            </div>

            <!-- MASCOTA -->
            <div class="mascota">
                <label>Mascota</label>

                <!--<input type="file" name="i_mascota" id="archivo-mascota" accept="image/*" required style="display:none;">-->

                <input type="file" name="i_mascota" id="archivo-mascota" accept="image/*" required style="opacity:0; position:absolute; width:0; height:0;">


                <button type="button" class="multimedia" onclick="document.getElementById('archivo-mascota').click()">
                    <i class="bi bi-image"></i>
                </button>

                <img src="<?= BASE_URL ?>public/imagenes/FDP.png" class="imagen-mundial" id="preview-mascota">
            </div>

            <!-- BOTÓN -->
<button type="submit" id="btn-publicar" name="btn_agregar_mundial">
    Agregar Mundial
</button>

        </form>

        <!-- ===========================
             SCRIPTS
        ============================ -->

        <!-- TAGIFY -->
        <script>
        document.addEventListener("DOMContentLoaded", function () {

            const equiposInput = document.getElementById("input-equipos");
            const tagify = new Tagify(equiposInput);

            document.getElementById("form-mundial").addEventListener("submit", function() {
                const listaEquipos = tagify.value.map(tag => tag.value);
                document.getElementById("equipos_json").value = JSON.stringify(listaEquipos);
            });

        });
        </script>

        <!-- PREVIEW DE IMÁGENES -->
        <script>
        function previewFile(input, imgElementId) {
            input.addEventListener("change", () => {
                const file = input.files[0];
                if (file) {
                    document.getElementById(imgElementId).src = URL.createObjectURL(file);
                }
            });
        }

        previewFile(document.getElementById('archivo-icono'), 'preview-icono');
        previewFile(document.getElementById('archivo-copa'), 'preview-copa');
        previewFile(document.getElementById('archivo-mascota'), 'preview-mascota');
        </script>

    </div>
</div>

<h1 id="footer">Whats The Cup. Todos los derechos reservados</h1>

<script>
    const BASE_URL = "<?= BASE_URL ?>";
</script>

<script src="<?= BASE_URL ?>public/js/Header.js"></script>
<script src="<?= BASE_URL ?>public/js/SidebarAdmin.js"></script>

</body>
</html>
