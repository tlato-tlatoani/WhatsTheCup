<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '/WhatsTheCup/Conexion.php';

$conn = (new Conexion())->getConnection();

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    header("Location: /WhatsTheCup/index.php");
    exit;
}

$errores = $_SESSION['errores_actualizacion'] ?? [];
$mensaje_exito = $_SESSION['mensaje_exito'] ?? '';
$datos = $_SESSION['datos_actualizacion'] ?? [];

unset($_SESSION['errores_actualizacion'], $_SESSION['mensaje_exito'], $_SESSION['datos_actualizacion']);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_actualizar'])) {
    $nombres      = $_POST['NOMBRES'];
    $apellido_p   = $_POST['APELLIDO_P'];
    $apellido_m   = $_POST['APELLIDO_M'];
    $correo       = $_POST['CORREO'];
    $contrasenna  = $_POST['CONTRASENNA'];
    $nacimiento   = $_POST['NACIMIENTO'];
    $genero       = $_POST['GENERO'];
    $nacionalidad = $_POST['NACIONALIDAD'];
    $pais_origen  = $_POST['PAIS_ORIGEN'];

    // Validaciones
    if (strlen($contrasenna) < 6) {
        $errores['contrasenna'] = "La contraseña debe tener al menos 6 caracteres.";
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = "Correo no válido.";
    }
    if (!$nacimiento) {
        $errores['nacimiento'] = "Debe ingresar una fecha de nacimiento.";
    }

    if (!empty($errores)) {
        $_SESSION['errores_actualizacion'] = $errores;
        $_SESSION['datos_actualizacion'] = $_POST;
        header("Location: /WhatsTheCup/app/views/user-views/Us-Perfil.php");
        exit;
    }

    // Hash de contraseña
    $contrasenna_hash = password_hash($contrasenna, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("CALL sp_actualizar_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $id_usuario,
            $nombres,
            $apellido_p,
            $apellido_m,
            $nacimiento,
            $genero,
            $nacionalidad,
            $pais_origen,
            $correo,
            $contrasenna_hash
        ]);

        $_SESSION['mensaje_exito'] = "Perfil actualizado correctamente.";
        header("Location: /WhatsTheCup/app/views/user-views/Us-Perfil.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['errores_actualizacion'] = ['general' => $e->getMessage()];
        $_SESSION['datos_actualizacion'] = $_POST;
        header("Location: /WhatsTheCup/app/views/user-views/Us-Perfil.php");
        exit;
    }
}

// Obtener datos del usuario
try {
    $stmt = $conn->prepare("CALL sp_obtener_usuario(?)");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $usuario = [];
    $errores['general'] = "No se pudieron cargar los datos del usuario.";
}

// Si hay datos ingresados previamente (por error), los sobreescribe
if (!empty($datos)) {
    $usuario = array_merge($usuario, $datos);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Perfil de Usuario</title>
<link rel="stylesheet" href="/WhatsTheCup/public/css/Us_Perfil.css">
<link rel="stylesheet" href="/WhatsTheCup/public/css/Fuentes.css">
</head>
<body>

<div class="container-perfil">
    <h1>Editar Perfil</h1>

    <?php if($mensaje_exito): ?>
        <p class="mensaje-exito"><?php echo htmlspecialchars($mensaje_exito); ?></p>
    <?php endif; ?>
    <?php if(isset($errores['general'])): ?>
        <p class="error-message"><?php echo htmlspecialchars($errores['general']); ?></p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">

        <!-- Foto de perfil -->
        <div class="upload">
            <div class="circle" id="profilePicCircle" 
                 style="background-image: url('<?php echo htmlspecialchars($usuario['IMAGEN_PERFIL']); ?>'); background-size: cover;"></div>
            <input type="file" id="file" name="IMAGEN_PERFIL" style="display:none;">
            <button type="button" onclick="document.getElementById('file').click()">Cambiar foto</button>
        </div>

        <!-- Datos personales -->
        <label>Nombre(s)
            <input type="text" name="NOMBRES" value="<?php echo htmlspecialchars($usuario['NOMBRES']); ?>" required>
        </label>

        <label>Apellido Paterno
            <input type="text" name="APELLIDO_P" value="<?php echo htmlspecialchars($usuario['APELLIDO_P']); ?>" required>
        </label>

        <label>Apellido Materno
            <input type="text" name="APELLIDO_M" value="<?php echo htmlspecialchars($usuario['APELLIDO_M']); ?>" required>
        </label>

        <label>Correo
            <input type="email" name="CORREO" value="<?php echo htmlspecialchars($usuario['CORREO']); ?>" required>
        </label>
        <?php if(isset($errores['correo'])): ?>
            <p class="error-message"><?php echo $errores['correo']; ?></p>
        <?php endif; ?>

        <label>Contraseña
            <input type="password" name="CONTRASENNA" value="<?php echo htmlspecialchars($usuario['CONTRASENNA']); ?>" required>
        </label>
        <?php if(isset($errores['contrasenna'])): ?>
            <p class="error-message"><?php echo $errores['contrasenna']; ?></p>
        <?php endif; ?>

        <label>Fecha de nacimiento
            <input type="date" name="NACIMIENTO" value="<?php echo htmlspecialchars($usuario['NACIMIENTO']); ?>" required>
        </label>
        <?php if(isset($errores['nacimiento'])): ?>
            <p class="error-message"><?php echo $errores['nacimiento']; ?></p>
        <?php endif; ?>

        <label>Género:</label>
        <label><input type="radio" name="GENERO" value="F" <?php echo ($usuario['GENERO'] == 'F') ? 'checked' : ''; ?>> Femenino</label>
        <label><input type="radio" name="GENERO" value="M" <?php echo ($usuario['GENERO'] == 'M') ? 'checked' : ''; ?>> Masculino</label>

        <label>Nacionalidad
            <input type="text" name="NACIONALIDAD" value="<?php echo htmlspecialchars($usuario['NACIONALIDAD']); ?>" required>
        </label>

        <label>País de origen
            <input type="text" name="PAIS_ORIGEN" value="<?php echo htmlspecialchars($usuario['PAIS_ORIGEN']); ?>" required>
        </label>

        <div class="btn">
            <button type="submit" name="btn_actualizar">Guardar cambios</button>
        </div>
    </form>
</div>

<script>
// Preview de la imagen
document.getElementById('file').addEventListener('change', function(e) {
    const circle = document.getElementById('profilePicCircle');
    const file = e.target.files[0];
    if(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            circle.style.backgroundImage = `url(${e.target.result})`;
            circle.style.backgroundSize = 'cover';
        }
        reader.readAsDataURL(file);
    }
});
</script>
</body>
</html>