<?php

require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'Conexion.php'; 

class UserModel {
    private $db;

    public function __construct() {
        //se inicializa la conexión
        $this->db = new Conexion(); 
    }

    /**
     * Registra un nuevo usuario llamando al Stored Procedure sp_registrar_usuario.
     * @param array $datos Array asociativo con todos los datos del formulario.
     * @return bool|array Retorna TRUE si el registro es exitoso. Retorna un array con 'errorInfo' si hay un fallo de BD.
     */
    public function registrarUsuario(array $datos): bool|array {
        
    
        $NOMBRES = $datos['NOMBRES'];
        $APELLIDO_P = $datos['APELLIDO_P'];
        $APELLIDO_M = $datos['APELLIDO_M'];
        $NACIMIENTO = $datos['NACIMIENTO'];
        $GENERO = $datos['GENERO'];
        $NACIONALIDAD = $datos['NACIONALIDAD'];
        $PAIS_ORIGEN = $datos['PAIS_ORIGEN'];
        $CORREO = $datos['CORREO'];
        $CONTRASENNA = $datos['CONTRASENNA']; 
        $IMAGEN_PERFIL_BLOB = $datos['IMAGEN_PERFIL_BLOB'];

        // Consulta SQL para el Stored Procedure
        $sql = "CALL sp_registrar_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
        
        try {
            $pdo = $this->db->getConnection(); 
            $stmt = $pdo->prepare($sql);

            //parámetros
            $stmt->bindParam(1, $NOMBRES, PDO::PARAM_STR);
            $stmt->bindParam(2, $APELLIDO_P, PDO::PARAM_STR);
            $stmt->bindParam(3, $APELLIDO_M, PDO::PARAM_STR);
            $stmt->bindParam(4, $NACIMIENTO, PDO::PARAM_STR); 
            $stmt->bindParam(5, $GENERO, PDO::PARAM_STR);
            $stmt->bindParam(6, $NACIONALIDAD, PDO::PARAM_STR);
            $stmt->bindParam(7, $PAIS_ORIGEN, PDO::PARAM_STR);
            $stmt->bindParam(8, $CORREO, PDO::PARAM_STR);
            $stmt->bindParam(9, $CONTRASENNA, PDO::PARAM_STR); 

            if ($IMAGEN_PERFIL_BLOB === NULL) {
                $stmt->bindValue(10, null, PDO::PARAM_NULL);
            } else {
               
                $stmt->bindParam(10, $IMAGEN_PERFIL_BLOB, PDO::PARAM_LOB); 
            }

            $ejecucionExitosa = $stmt->execute();
            
        //    if ($ejecucionExitosa) {
                // Si la ejecución fue exitosa, retornar TRUE
        //        return $pdo->lastInsertId();
      //    } else {
                // Si execute() devuelve false, capturamos la información de error.
          //      return ['errorInfo' => $stmt->errorInfo()];
        //    }


            $ejecucionExitosa = $stmt->execute();
            
            if ($ejecucionExitosa) {
                // Cierra el cursor (es obligatorio después de ejecutar un SP)
                $stmt->closeCursor(); 
                
                // --- MODIFICACIÓN CLAVE ---
                $nuevoId = $pdo->lastInsertId();
                
                // Si lastInsertId es 0 o una cadena vacía, retornamos 1
                // (Opcional, solo si el SP no maneja la inserción directa)
                if (empty($nuevoId) || !is_numeric($nuevoId)) {
                   // Si no obtenemos un ID, asumimos que algo salió mal o el SP es complejo. 
                   // En este caso, lo mejor es devolver un error o 0.
                   return 0; 
                }
                
                return $nuevoId; // Retorna el ID numérico
                // --------------------------
            } else {
                // Si execute() devuelve false, capturamos la información de error.
                $stmt->closeCursor(); // Asegura el cierre del cursor
                return ['errorInfo' => $stmt->errorInfo()];
            }








            
        } catch (PDOException $e) {
            // Manejo de excepción de PDO (ej. error de conexión)
            error_log('Error PDO en UserModel: ' . $e->getMessage()); 
            return ['errorInfo' => ['PDOException', $e->getCode(), $e->getMessage()]];
        } finally {
            if ($stmt) {
                $stmt->closeCursor();
            }
        }
    }

    public function iniciarSesion(string $correo, string $contrasenna): ?array {
        
        $sql = "CALL sp_iniciar_sesion(?, ?)"; 
        
        try {
            $pdo = $this->db->getConnection();
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([$correo, $contrasenna]);
           
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Cierra el cursor para permitir otras consultas 
            $stmt->closeCursor();

            // Si se encontró un usuario, retorna el array de datos, de lo contrario, retorna null.
            return $usuario ? $usuario : null;

        } catch (PDOException $e) {
            error_log('Error PDO en UserModel::iniciarSesion: ' . $e->getMessage()); 
            return ['db_error' => 'Error de Base de Datos.']; 
        }
    }

    public function consultarUsuarioPorId(int $id_usuario): ?array {
        $sql = "CALL sp_consultar_usuarios(?)"; 
        
        try {
            $pdo = $this->db->getConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_usuario]);
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            // Retorna los datos o null si no se encontró
            return $usuario ?: null; 

        } catch (PDOException $e) {
            error_log("Error de BD en consultarUsuarioPorId: " . $e->getMessage());
            return null; // Devuelve null si hay un error de conexión/consulta grave
        }
    }

    public function actualizarUsuario(int $id_usuario, array $datos): bool|string {
        
        // Extraer y asignar los datos del array
        $nombres      = $datos['nombres'];
        $apellido_p   = $datos['apellido_p'];
        $apellido_m   = $datos['apellido_m'];
        $nacimiento   = $datos['nacimiento'];
        $genero       = $datos['genero'];
        $nacionalidad = $datos['nacionalidad'];
        $pais_origen  = $datos['pais_origen'];
        $correo       = $datos['correo'];
        $contrasenna  = $datos['contrasenna'];
        $imagen_perfil= $datos['imagen_perfil'];

        $sql = "CALL sp_actualizar_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
        
        try {
            $pdo = $this->db->getConnection();
            $stmt = $pdo->prepare($sql);
    
            $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(2, $nombres, PDO::PARAM_STR);
            $stmt->bindParam(3, $apellido_p, PDO::PARAM_STR);
            $stmt->bindParam(4, $apellido_m, PDO::PARAM_STR);
            $stmt->bindParam(5, $nacimiento, PDO::PARAM_STR);
            $stmt->bindParam(6, $genero, PDO::PARAM_STR);
            $stmt->bindParam(7, $nacionalidad, PDO::PARAM_STR);
            $stmt->bindParam(8, $pais_origen, PDO::PARAM_STR);
            $stmt->bindParam(9, $correo, PDO::PARAM_STR);
            $stmt->bindParam(10, $contrasenna, PDO::PARAM_STR);
            
            if ($imagen_perfil === NULL) {
                $stmt->bindValue(11, null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(11, $imagen_perfil, PDO::PARAM_LOB); 
            }

            $resultado = $stmt->execute();
            $stmt->closeCursor();

            if ($resultado) {
                return true; 
            } else {
                
                $errorInfo = $stmt->errorInfo();
                return "Error DB: " . ($errorInfo[2] ?? 'Error desconocido.');
            }
        } catch (PDOException $e) {
            error_log("Error de BD en actualizarUsuario: " . $e->getMessage());
            return "Error de BD: " . $e->getMessage();
        }
    }

    
    public function consultarCorreoExistente(string $correo): bool|array {
        $sql = "SELECT COUNT(ID_USUARIO) FROM USUARIO WHERE CORREO = ?"; 
        
        try {
            $pdo = $this->db->getConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$correo]);
            
            $count = $stmt->fetchColumn(); // Obtiene el valor de COUNT()
            $stmt->closeCursor();

            return ($count > 0); // Retorna TRUE si el conteo es mayor a 0 (existe)
            
        } catch (PDOException $e) {
            error_log('Error PDO en UserModel::consultarCorreoExistente: ' . $e->getMessage()); 
            // Retorna array para indicar un fallo grave de BD
            return ['errorInfo' => ['PDOException', $e->getCode(), $e->getMessage()]];
        }
    }

    
public function buscarUsuarioOAuth(string $googleId, string $correo): ?array {
    $sql = "CALL sp_buscar_usuario_oauth(?, ?)";

    try {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare($sql);
        
        // Pasa GOOGLE_ID y CORREO al SP
        $stmt->execute([$googleId, $correo]);
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $usuario ?: null;

    } catch (PDOException $e) {
        error_log("Error de BD en buscarUsuarioOAuth: " . $e->getMessage());
        return null;
    }
}


public function asociarGoogleId($id_usuario, $google_id) {
    // 1. Obtener la conexión PDO real
    $pdo = $this->db->getConnection(); 

    // 2. Usar prepare() sobre el objeto PDO
    $query = $pdo->prepare("CALL sp_asociar_google_id(?, ?)"); 
    $query->execute([$id_usuario, $google_id]);
    
    // Opcional: Cerrar cursor
    $query->closeCursor();
}

}



