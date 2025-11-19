DROP VIEW IF EXISTS v_publicaciones_pendientes;

CREATE VIEW v_publicaciones_pendientes AS
SELECT
    p.id,
    p.titulo,
    p.descripcion,
    p.FECHA_PUBLICACION,
    p.ESTATUS,
    p.AprobadoAdmin,
    p.MULTIMEDIA AS multimedia_id,
    p.mundial_id,
    p.categoria_id,

    -- Datos del Usuario (Autor)
    p.autor_id,
    CONCAT(u.NOMBRES, ' ', u.APELLIDO_P) AS nombre_autor_completo,
    u.CORREO AS correo_autor,

    -- Datos de la Categoría
    c.nombre AS nombre_categoria,

    -- Datos multimedia
    m.nombre_archivo,
    m.tipo_mime,
    TO_BASE64(m.contenido) AS base64_multimedia

FROM 
    PUBLICACION p
JOIN 
    USUARIO u ON p.autor_id = u.ID_USUARIO
JOIN 
    categorias c ON p.categoria_id = c.id
LEFT JOIN
    multimedia m ON p.MULTIMEDIA = m.id

WHERE 
    p.ESTATUS = 'pendiente'
    AND p.AprobadoAdmin = 0;
    
    
    CREATE VIEW vw_publicacion_detalles AS
SELECT
    p.id,
    p.titulo,
    p.descripcion,
    c.nombre AS nombre_categoria,
    CONCAT(u.NOMBRES, ' ', u.APELLIDO_P) AS nombre_autor_completo,
    p.fecha_publicacion,
    m.nombre_archivo,
    m.tipo_mime,
    TO_BASE64(m.contenido) AS base64_multimedia,
    p.AprobadoAdmin
FROM
    publicacion p
JOIN usuario u ON p.autor_id = u.ID_USUARIO
JOIN categorias c ON p.categoria_id = c.id
LEFT JOIN multimedia m ON p.MULTIMEDIA = m.id;


CREATE VIEW vw_publicaciones_con_multimedia AS
SELECT
    p.id,
    p.titulo,
    p.descripcion,
    p.fecha_publicacion,
    p.estatus,
    p.categoria_id,
    p.mundial_id,
    p.autor_id,            -- Incluir autor_id para poder filtrar en PHP
    m.nombre_archivo,
    m.tipo_mime,
    TO_BASE64(m.contenido) AS multimedia_base64
FROM Publicacion p
LEFT JOIN Multimedia m ON p.MULTIMEDIA = m.id;

CREATE VIEW vw_publicaciones_completas AS
SELECT
    p.id,
    p.titulo,
    p.descripcion,
    p.fecha_publicacion,
    p.estatus,
    p.AprobadoAdmin, -- Incluir el estado de aprobación para el filtro
    p.mundial_id,    -- Incluir el ID del mundial para el filtro dinámico
    c.nombre AS nombre_categoria,
    CONCAT(u.NOMBRES, ' ', u.APELLIDO_P) AS nombre_autor_completo,
    m.nombre_archivo,
    m.tipo_mime,
    TO_BASE64(m.contenido) AS base64_multimedia
FROM
    publicacion p
JOIN usuario u ON p.autor_id = u.ID_USUARIO
JOIN categorias c ON p.categoria_id = c.id
LEFT JOIN multimedia m ON p.MULTIMEDIA = m.id;



CREATE VIEW vista_comentarios_detalles AS
SELECT
    c.id AS comentario_id,
    c.contenido,
    c.fecha_creacion,
    c.publicacion_id,
    c.usuario_id AS autor_id,
    CONCAT(u.NOMBRES, ' ', u.APELLIDO_P) AS nombre_usuario
FROM
    comentarios c
JOIN
    usuario u ON c.usuario_id = u.ID_USUARIO
WHERE
    c.activo = TRUE;