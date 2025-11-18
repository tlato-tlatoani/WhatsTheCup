-- Sentencia para eliminar la vista si ya existe
DROP VIEW IF EXISTS v_publicaciones_pendientes;

-- Sentencia para crear la vista
CREATE VIEW v_publicaciones_pendientes AS
SELECT
    p.ID_PUBLICACION,
    p.TITULO,
    p.descripcion,
    p.FECHA_PUBLICACION, -- Aunque estará nula/vacía, es bueno incluirla
    p.ESTATUS,
    p.AprobadoAdmin,
    p.MULTIMEDIA,
    p.mundial_id,
    p.categoria_id,
    
    -- Datos del Usuario (Autor)
    p.autor_id,
    CONCAT(u.NOMBRES, ' ', u.APELLIDO_P) AS nombre_autor_completo,
    u.CORREO AS correo_autor,
    
    -- Datos de la Categoría
    c.nombre AS nombre_categoria

FROM 
    PUBLICACION p
JOIN 
    USUARIO u ON p.autor_id = u.ID_USUARIO -- Usa autor_id en PUBLICACION y ID_USUARIO en USUARIO
JOIN 
    categoria c ON p.categoria_id = c.id   -- Usa categoria_id en PUBLICACION y id en categoria

WHERE 
    p.ESTATUS = 'pendiente' 
    AND p.AprobadoAdmin = 0; -- Aunque ESTATUS='pendiente' ya implica AprobadoAdmin=0, es buena práctica mantener la condición para claridad.