<?php
//archivo de conexión
require_once __DIR__ . '/../../Conexion.php'; // Asumiendo que registro.php y Conexion.php están al mismo nivel o ajusta la ruta.**

if (isset($_POST['btn_registrar'])) {
    //Recoger y Sanitizar Datos del Formulario
    $NOMBRES = htmlspecialchars($_POST['NOMBRES']);
    $APELLIDO_P = htmlspecialchars($_POST['APELLIDO_P']);
    $APELLIDO_M = htmlspecialchars($_POST['APELLIDO_M']);
    $NACIMIENTO = $_POST['NACIMIENTO ']; // validarla*
    $GENERO = $_POST['GENERO'];
    $NACIONALIDAD = htmlspecialchars($_POST['NACIONALIDAD']);
    $PAIS_ORIGEN = htmlspecialchars($_POST['PAIS_ORIGEN']);
    $CORREO = filter_var($_POST['CORREO'], FILTER_SANITIZE_EMAIL);
    $CONTRASENNA = htmlspecialchars($_POST['CONTRASENNA']);


    // Conexión y Llamada al Stored Procedure
    try {
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();

        // Query para llamar al Stored Procedure (usando PDO y marcadores de posición)
        $sql = "CALL sp_registrar_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // 4. Ejecutar con los parámetros
  
        $stmt->execute([
            $NOMBRES,
            $APELLIDO_P,
            $APELLIDO_M,
            $NACIMIENTO,
            $GENERO,
            $NACIONALIDAD,
            $PAIS_ORIGEN,
            $CORREO,
            $CONTRASENNA 
        ]);

       header("Location: /WhatsTheCup/public/index.php?route=iniciarsesion&status=success");
        exit(); 

    } catch (PDOException $e) {
        // Manejo de error de base de datos
        // En un entorno de producción, nunca muestres $e->getMessage() al usuario final
        echo "Error de registro: " . $e->getMessage(); 
        // Lógica para mostrar un error amigable o redireccionar
    }
}
?>