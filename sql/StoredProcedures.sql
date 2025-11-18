DELIMITER $$

CREATE PROCEDURE sp_registrar_usuario (
    IN p_NOMBRES VARCHAR(20),
    IN p_APELLIDO_P VARCHAR(20),
    IN p_APELLIDO_M VARCHAR(20),
    IN p_NACIMIENTO DATE,
    IN p_GENERO ENUM('M', 'F'),
    IN p_NACIONALIDAD VARCHAR(20),
    IN p_PAIS_ORIGEN VARCHAR(20),
    IN p_CORREO VARCHAR(30),
    IN p_CONTRASENNA VARCHAR(70),
    IN p_IMAGEN_PERFIL LONGBLOB
)
BEGIN
    INSERT INTO USUARIO (
        NOMBRES,
        APELLIDO_P,
        APELLIDO_M,
        NACIMIENTO,
        GENERO,
        NACIONALIDAD,
        PAIS_ORIGEN,
        CORREO,
        CONTRASENNA,
        IMAGEN_PERFIL,
        TIPO_USUARIO
    ) VALUES (
        p_NOMBRES,
        p_APELLIDO_P,
        p_APELLIDO_M,
        p_NACIMIENTO,
        p_GENERO,
        p_NACIONALIDAD,
        p_PAIS_ORIGEN,
        p_CORREO,
        p_CONTRASENNA,
        p_IMAGEN_PERFIL,
        'USUARIO'
    );
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_actualizar_usuario (
    IN p_ID_USUARIO INT,
    IN p_NOMBRES VARCHAR(20),
    IN p_APELLIDO_P VARCHAR(20),
    IN p_APELLIDO_M VARCHAR(20),
    IN p_NACIMIENTO DATE,
    IN p_GENERO ENUM('M', 'F'),
    IN p_NACIONALIDAD VARCHAR(20),
    IN p_PAIS_ORIGEN VARCHAR(20),
    IN p_CORREO VARCHAR(30),
    IN p_CONTRASENNA VARCHAR(30),
    IN p_IMAGEN_PERFIL LONGBLOB
)
BEGIN
    -- Verifica si el usuario existe antes de actualizar
    IF EXISTS (SELECT 1 FROM USUARIO WHERE ID_USUARIO = p_ID_USUARIO) THEN
        UPDATE USUARIO
        SET 
            NOMBRES = p_NOMBRES,
            APELLIDO_P = p_APELLIDO_P,
            APELLIDO_M = p_APELLIDO_M,
            NACIMIENTO = p_NACIMIENTO,
            GENERO = p_GENERO,
            NACIONALIDAD = p_NACIONALIDAD,
            PAIS_ORIGEN = p_PAIS_ORIGEN,
            CORREO = p_CORREO,
            CONTRASENNA = p_CONTRASENNA,
            IMAGEN_PERFIL = p_IMAGEN_PERFIL
        WHERE ID_USUARIO = p_ID_USUARIO;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El usuario con ese ID no existe.';
    END IF;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_consultar_usuarios (
    IN p_ID_USUARIO INT
)
BEGIN
    IF p_ID_USUARIO IS NULL THEN
        --  Consulta todos los usuarios
        SELECT 
            ID_USUARIO,
            NOMBRES,
            APELLIDO_P,
            APELLIDO_M,
            NACIMIENTO,
            GENERO,
            NACIONALIDAD,
            PAIS_ORIGEN,
            CORREO,
            IMAGEN_PERFIL,
            TIPO_USUARIO
        FROM usuario;
    ELSE
        --  Consulta un usuario específico
        SELECT 
            ID_USUARIO,
            NOMBRES,
            APELLIDO_P,
            APELLIDO_M,
            NACIMIENTO,
            GENERO,
            NACIONALIDAD,
            PAIS_ORIGEN,
            CORREO,
            IMAGEN_PERFIL,
            TIPO_USUARIO
        FROM usuario
        WHERE ID_USUARIO = p_ID_USUARIO;
    END IF;
END $$

DELIMITER ;

USE WTC;
DELIMITER $$
CREATE PROCEDURE sp_iniciar_sesion(
    IN p_CORREO VARCHAR(30),
    IN p_CONTRASENNA VARCHAR(30) 
)
BEGIN
    SELECT 
        ID_USUARIO, 
        NOMBRES, CONTRASENNA	
        CORREO, 
        TIPO_USUARIO 
    FROM 
        USUARIO
    WHERE 
        CORREO = p_CORREO AND CONTRASENNA = p_CONTRASENNA;

END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_asociar_google_id;
DELIMITER $$

CREATE PROCEDURE sp_asociar_google_id (
    IN p_ID_USUARIO INT,
    IN p_GOOGLE_ID VARCHAR(255)
)
BEGIN
    -- Evitar asignar Google ID si ya está en uso por otro usuario
    IF EXISTS (SELECT 1 FROM USUARIO WHERE GOOGLE_ID = p_GOOGLE_ID) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El GOOGLE_ID ya está asociado a otro usuario.';
    END IF;

    -- Actualizar el usuario objetivo
    UPDATE USUARIO
    SET GOOGLE_ID = p_GOOGLE_ID
    WHERE ID_USUARIO = p_ID_USUARIO;
END$$

DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_registrar_mundial(
    IN p_anio YEAR,
    IN p_titulo VARCHAR(100),
    IN p_descripcion TEXT,
    IN p_equipos TEXT,
    IN p_detalles JSON,
    IN p_sedes JSON,

    IN p_banner_nombre VARCHAR(255),
    IN p_banner_mime VARCHAR(100),
    IN p_banner_contenido LONGBLOB,

    IN p_copa_nombre VARCHAR(255),
    IN p_copa_mime VARCHAR(100),
    IN p_copa_contenido LONGBLOB,

    IN p_mascota_nombre VARCHAR(255),
    IN p_mascota_mime VARCHAR(100),
    IN p_mascota_contenido LONGBLOB,

    IN p_admin INT
)
BEGIN
    DECLARE nuevo_id INT;
    DECLARE i INT DEFAULT 0;
    DECLARE sede_nombre VARCHAR(100);
    DECLARE sede_id INT;

    -- Insertar Mundial
    INSERT INTO Mundial(anio, titulo, descripcion, equipos, detalles, CreadoAdmin)
    VALUES(p_anio, p_titulo, p_descripcion, p_equipos, p_detalles, p_admin);

    SET nuevo_id = LAST_INSERT_ID();


 INSERT INTO Multimedia(nombre_archivo, tipo_mime, contenido)
    VALUES(p_banner_nombre, p_banner_mime, p_banner_contenido);

    INSERT INTO Multimedia_mundial(multimedia_id, mundial_id, es_banner, es_copa, es_mascota)
    VALUES(LAST_INSERT_ID(), nuevo_id, 1, 0, 0);

    -- Insertar Copa
    INSERT INTO Multimedia(nombre_archivo, tipo_mime, contenido)
    VALUES(p_copa_nombre, p_copa_mime, p_copa_contenido);

    INSERT INTO Multimedia_mundial(multimedia_id, mundial_id, es_banner, es_copa, es_mascota)
    VALUES(LAST_INSERT_ID(), nuevo_id, 0, 1, 0);

    -- Insertar Mascota
    INSERT INTO Multimedia(nombre_archivo, tipo_mime, contenido)
    VALUES(p_mascota_nombre, p_mascota_mime, p_mascota_contenido);

    INSERT INTO Multimedia_mundial(multimedia_id, mundial_id, es_banner, es_copa, es_mascota)
    VALUES(LAST_INSERT_ID(), nuevo_id, 0, 0, 1);

    -- INSERTAR SEDES EN TABLAS Pais → Sede
    WHILE i < JSON_LENGTH(p_sedes) DO

        SET sede_nombre = JSON_UNQUOTE(JSON_EXTRACT(p_sedes, CONCAT('$[', i, ']')));

        -- Buscar si ya existe el país:
        SELECT id INTO sede_id FROM Pais WHERE pais = sede_nombre LIMIT 1;

        IF sede_id IS NULL THEN
            INSERT INTO Pais(pais) VALUES(sede_nombre);
            SET sede_id = LAST_INSERT_ID();
        END IF;

        INSERT INTO Sede(mundial_id, sede_id)
        VALUES(nuevo_id, sede_id);

        SET i = i + 1;
    END WHILE;

    -- Regresar ID
    SELECT nuevo_id AS id_mundial;

END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_agregar_categoria(
    IN p_nombre VARCHAR(100),
    IN p_admin INT
)
BEGIN
    INSERT INTO Categorias(nombre, CreadoAdmin)
    VALUES(p_nombre, p_admin);

    SELECT LAST_INSERT_ID() AS id_categoria;
END$$

DELIMITER ;

DROP PROCEDURE sp_obtener_mundial_por_id;
DELIMITER $$
CREATE PROCEDURE `sp_obtener_mundial_por_id`(IN p_id INT)
BEGIN
  SELECT 
    m.*,

    -- BANNER
    b.nombre_archivo AS banner_nombre,
    b.tipo_mime AS banner_mime,
    TO_BASE64(b.contenido) AS banner_base64,

    -- COPA
    c.nombre_archivo AS copa_nombre,
    c.tipo_mime AS copa_mime,
    TO_BASE64(c.contenido) AS copa_base64,

    -- MASCOTA
    ma.nombre_archivo AS mascota_nombre,
    ma.tipo_mime AS mascota_mime,
    TO_BASE64(ma.contenido) AS mascota_base64

  FROM Mundial m

  -- Banner
  LEFT JOIN Multimedia_mundial mm_b ON mm_b.mundial_id = m.id AND mm_b.es_banner = 1
  LEFT JOIN Multimedia b ON b.id = mm_b.multimedia_id

  -- Copa
  LEFT JOIN Multimedia_mundial mm_c ON mm_c.mundial_id = m.id AND mm_c.es_copa = 1
  LEFT JOIN Multimedia c ON c.id = mm_c.multimedia_id

  -- Mascota
  LEFT JOIN Multimedia_mundial mm_ma ON mm_ma.mundial_id = m.id AND mm_ma.es_mascota = 1
  LEFT JOIN Multimedia ma ON ma.id = mm_ma.multimedia_id

  WHERE m.id = p_id;
END $$
DELIMITER ;

DELIMITER $$

DELIMITER $$
CREATE PROCEDURE sp_listar_mundiales()
BEGIN
    SELECT 
        m.id,
        m.anio,
        m.titulo,
        m.descripcion,
        m.equipos,

        -- FOTO PRINCIPAL (puede ser NULL)
        mm.multimedia_id,
        md.nombre_archivo,
        md.tipo_mime,
        TO_BASE64(md.contenido) AS imagen_base64

    FROM Mundial m
    LEFT JOIN Multimedia_mundial mm 
        ON mm.mundial_id = m.id AND mm.es_banner = 1
    LEFT JOIN Multimedia md
        ON md.ID_MULTIMEDIA = mm.multimedia_id

    ORDER BY m.anio DESC;
END $$

DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_obtener_mundiales`()
BEGIN
  SELECT 
    m.id,
    m.titulo,
    m.anio,

    md.nombre_archivo AS banner_nombre,
    md.tipo_mime   AS banner_mime,
    TO_BASE64(md.contenido) AS banner_base64

  FROM Mundial m
  LEFT JOIN Multimedia_mundial mm 
    ON mm.mundial_id = m.id AND mm.es_banner = 1
  LEFT JOIN Multimedia md
    ON md.id = mm.multimedia_id

  ORDER BY m.id DESC;
END $$
DELIMITER ;


DELIMITER $$

CREATE FUNCTION json_to_text_list(p_json JSON)
RETURNS VARCHAR(500)
DETERMINISTIC
BEGIN
    DECLARE resultado VARCHAR(500);

    SELECT GROUP_CONCAT(JSON_UNQUOTE(JSON_EXTRACT(p_json, CONCAT('$[', n.n, ']'))) SEPARATOR ',')
    INTO resultado
    FROM (
        SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION
        SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION 
        SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14
    ) n
    WHERE n.n < JSON_LENGTH(p_json);

    RETURN resultado;
END $$

DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_agregar_categoria`(
  IN p_nombre VARCHAR(100),
  IN p_admin INT
)
BEGIN
  INSERT INTO Categorias(nombre, CreadoAdmin)
  VALUES(p_nombre, p_admin);

  SELECT LAST_INSERT_ID() AS id_categoria;
END $$
DELIMITER ;
