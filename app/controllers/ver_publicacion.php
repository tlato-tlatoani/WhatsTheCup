<?php


ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once PROJECT_ROOT . '/app/models/Model_Publicacion.php';

$id_publicacion = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$publicacion = null;
$error_publicacion = null;

if (!$id_publicacion) {
    $error_publicacion = "ID de publicación no válido.";
} else {
    try {
        $model = new Model_Publicacion();

        // Consulta especial para obtener UNA publicación aprobada con multimedia
        $sql = "
            SELECT 
                p.id, 
                p.titulo, 
                p.descripcion, 
                c.nombre AS nombre_categoria, 
                CONCAT(u.NOMBRES, ' ', u.APELLIDO_P) AS nombre_autor_completo,
                p.fecha_publicacion,
                m.nombre_archivo,
                m.tipo_mime,
                TO_BASE64(m.contenido) AS base64_multimedia
            FROM 
                publicacion p
            JOIN usuario u ON p.autor_id = u.ID_USUARIO
            JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN multimedia m ON p.MULTIMEDIA = m.id
            WHERE 
                p.id = :p_id
                AND p.AprobadoAdmin = 1
            LIMIT 1
        ";

        $conn = $model->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':p_id', $id_publicacion, PDO::PARAM_INT);
        $stmt->execute();

        $publicacion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$publicacion) {
            $error_publicacion = "La publicación no existe o no está aprobada.";
        }

    } catch (Exception $e) {
        error_log("Error al cargar publicación individual: " . $e->getMessage());
        $error_publicacion = "Error en el servidor al cargar la publicación.";
    }
}
