<?php
require_once dirname(__DIR__, 2) . '/Conexion.php';


class Model_Comentario
{
   private $conn;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->getConnection();
    }
    /**
     * Agrega un nuevo comentario usando sp_agregar_comentario.
     */
    public function agregarComentario(int $publicacionId, int $usuarioId, string $contenido): int|false
{
    try {
        $stmt = $this->conn->prepare("CALL sp_agregar_comentario(?, ?, ?)");
        $stmt->execute([$publicacionId, $usuarioId, $contenido]);
        
        // El SP devuelve el ID. Lo capturamos y cerramos el cursor.
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado && isset($resultado['nuevo_id'])) {
            return (int)$resultado['nuevo_id'];
        }
        return false;

    } catch (PDOException $e) {
        error_log("Error al agregar comentario (SP): " . $e->getMessage());
        return false;
    }
}
    /**
     * Obtiene todos los comentarios activos usando sp_obtener_comentarios.
     */
    public function obtenerComentariosPorPublicacion(int $publicacionId): array
    {
        $stmt = $this->conn->prepare("CALL sp_obtener_comentarios(?)");
        $stmt->execute([$publicacionId]);
        
        $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // [IMPORTANTE] Necesitas cerrar el cursor si vas a hacer otra consulta inmediatamente.
        $stmt->closeCursor(); 
        
        return $comentarios;
    }

    /**
     * Realiza la baja lógica de un comentario usando sp_eliminar_comentario.
     */
    public function eliminarComentario(int $comentarioId): bool
    {
        $stmt = $this->conn->prepare("CALL sp_eliminar_comentario(?)");
        return $stmt->execute([$comentarioId]);
    }

    /**
     * Cuenta los comentarios activos de una publicación usando sp_contar_comentarios.
     */
    public function contarComentarios(int $publicacionId): int
    {
        $stmt = $this->conn->prepare("CALL sp_contar_comentarios(?)");
        $stmt->execute([$publicacionId]);
        
        $count = $stmt->fetchColumn();
        $stmt->closeCursor(); 
        
        return (int)$count;
    }

    public function obtenerComentarioPorId(int $comentarioId): array
{
 
    $sql = "SELECT * FROM vista_comentarios_detalles WHERE comentario_id = :cid";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':cid' => $comentarioId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}


}