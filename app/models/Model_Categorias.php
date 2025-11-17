<?php
require_once dirname(__DIR__, 2) . '/Conexion.php';

class Model_Categorias {

    private $conn;

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->getConnection();
    }

    // INSERTAR CATEGORÍA
    public function agregarCategoria($nombre, $admin_id) {
        $stmt = $this->conn->prepare("CALL sp_agregar_categoria(?, ?)");
        $stmt->execute([$nombre, $admin_id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id_categoria'] ?? null;
    }

    // OBTENER TODAS LAS CATEGORÍAS
    public function obtenerCategorias() {
        $stmt = $this->conn->query("SELECT * FROM Categorias ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
