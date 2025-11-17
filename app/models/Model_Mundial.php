<?php

require_once dirname(__DIR__, 2) . '/Conexion.php';

class Model_Mundial {

    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->getConnection();
    }

    private function procesarArchivo($fileArr) {
        return [
            'nombre'    => $fileArr['name'] ?? null,
            'mime'      => $fileArr['type'] ?? null,
            'contenido' => (!empty($fileArr['tmp_name']) ? file_get_contents($fileArr['tmp_name']) : null)
        ];
    }

    /**
     * Registrar Mundial usando SP
     */
    public function registrarMundial($data, $files) 
    {


        // Procesar archivos BLOB
        $icono   = $this->procesarArchivo($files['i_imagen']);
        $copa    = $this->procesarArchivo($files['i_copa']);
        $mascota = $this->procesarArchivo($files['i_mascota']);

        // Preparar llamada al SP
        $stmt = $this->conn->prepare("
            CALL sp_registrar_mundial(
                ?, ?, ?, ?,     -- año, título, descripción, equiposTexto
                ?, ?,           -- detalles JSON, sedes JSON
                ?, ?, ?,        -- icono
                ?, ?, ?,        -- copa
                ?, ?, ?,        -- mascota
                ?               -- admin
            )
        ");

        // Ejecutar SP
        $stmt->execute([
            $data['anio'],
            $data['titulo'],
            $data['descripcion'],
            $data['equiposTexto'],

            $data['detallesJSON'],
            $data['sedesJSON'],

            $icono['nombre'],
            $icono['mime'],
            $icono['contenido'],

            $copa['nombre'],
            $copa['mime'],
            $copa['contenido'],

            $mascota['nombre'],
            $mascota['mime'],
            $mascota['contenido'],

            $data['admin_id']
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id_mundial'] ?? null;
    }

public function obtenerMundiales()
{
    $stmt = $this->conn->prepare("CALL sp_obtener_mundiales()");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor(); // importante para no bloquear siguientes llamadas

    return $result;
}

public function obtenerTodosLosMundiales() 
{

        $stmt = $this->conn->query("CALL sp_obtener_mundiales()");
        $mundiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $mundiales;
    }

    /* ============================================
       ➤ OBTENER UN MUNDIAL POR ID (para infografía)
       ============================================ */
    public function obtenerMundialPorId($id) 
    {

        $stmt = $this->conn->prepare("CALL sp_obtener_mundial_por_id(?)");
        $stmt->execute([$id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $data;
    }

public function listarMundiales() {
    $stmt = $this->conn->query("CALL sp_listar_mundiales()");
    $mundiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $mundiales;
}


}
