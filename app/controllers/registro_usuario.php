<?php

require_once PROJECT_ROOT . DIRECTORY_SEPARATOR . 'Conexion.php';

if (isset($_POST['btn_registrar'])) {
    
    // ==========================================================
    // 1. RECOGER DATOS Y SANITIZAR
    // ==========================================================
    $NOMBRES = htmlspecialchars($_POST['NOMBRES']);
    $APELLIDO_P = htmlspecialchars($_POST['APELLIDO_P']);
    $APELLIDO_M = htmlspecialchars($_POST['APELLIDO_M']);
    $NACIMIENTO = $_POST['NACIMIENTO']; 
    $GENERO = $_POST['GENERO'];
        if ($GENERO === "Masculino") {
             $GENERO = "M";
        } elseif ($GENERO === "Femenino") {
             $GENERO = "F";
        }
    $NACIONALIDAD = htmlspecialchars($_POST['NACIONALIDAD']);
    $PAIS_ORIGEN = htmlspecialchars($_POST['PAIS_ORIGEN']);
    $CORREO = filter_var($_POST['CORREO'], FILTER_SANITIZE_EMAIL);
    $CONTRASENNA = htmlspecialchars($_POST['CONTRASENNA']);
    
    // ==========================================================
    // 2. PROCESAR IMAGEN DE PERFIL
    // ==========================================================
    $imagen_perfil_blob = NULL;

        if (isset($_FILES['IMAGEN_PERFIL']) && $_FILES['IMAGEN_PERFIL']['error'] === UPLOAD_ERR_OK) {
    $imagen_perfil_blob = file_get_contents($_FILES['IMAGEN_PERFIL']['tmp_name']);
}


    // Conexión y Llamada al Stored Procedure
    try {
            $conexion = new Conexion();
            $pdo = $conexion->getConnection(); 

            $sql = "CALL sp_registrar_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);


       // Vincular los parámetros manualmente
        $stmt->bindParam(1, $NOMBRES);
        $stmt->bindParam(2, $APELLIDO_P);
        $stmt->bindParam(3, $APELLIDO_M);
        $stmt->bindParam(4, $NACIMIENTO);
        $stmt->bindParam(5, $GENERO);
        $stmt->bindParam(6, $NACIONALIDAD);
        $stmt->bindParam(7, $PAIS_ORIGEN);
        $stmt->bindParam(8, $CORREO);
        $stmt->bindParam(9, $CONTRASENNA);
        $stmt->bindParam(10, $imagen_perfil_blob, PDO::PARAM_LOB);

        $stmt->execute();

        // Registro exitoso: Redirección al inicio de sesión
       header("Location: " . BASE_URL . "index.php?route=iniciarsesion&status=success");
       exit(); // Crucial para que la redirección HTTP ocurra inmediatamente 

    } catch (PDOException $e) {
        // Manejo de error de base de datos
        // ... (Tu código de manejo de error)
        echo "Error de registro: " . $e->getMessage();
        echo "<p>Causa: " . $e->getMessage() . "</p>";
    }
  
}
?>