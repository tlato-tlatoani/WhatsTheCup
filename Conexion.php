<?php
class Conexion {
    private $host = 'localhost';
    private $dbname = 'bdm_proyecto';    // TU BD REAL
    private $user = 'root';
    private $password = '';
    private $port = '3307';              // PUERTO REAL
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};port={$this->port}",
                $this->user,
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>
