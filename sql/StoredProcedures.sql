DELIMITER $$

DROP PROCEDURE IF EXISTS sp_registrar_usuario;
-- Se elimina la versión antigua del procedimiento antes de crear la nueva.

CREATE PROCEDURE sp_registrar_usuario (
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


SELECT * FROM USUARIO;
CALL sp_actualizar_usuario(
    1,                         -- ID_USUARIO
    'Narayani',                -- NOMBRES
    'Cabrera',                 -- APELLIDO_P
    'Ramirez',                 -- APELLIDO_M (changed)
    '2004-02-10',              -- NACIMIENTO
    'F',                       -- GENERO
    'Mexicana',                -- NACIONALIDAD
    'México',                  -- PAIS_ORIGEN
    'narayani.cabrera@newmail.com',  -- CORREO (changed)
    'nuevaClave456',            -- CONTRASENNA (changed)
    null
);

DELIMITER $$

CREATE PROCEDURE sp_registrar_mundial (
    IN p_anio YEAR,
    IN p_titulo VARCHAR(100),
    IN p_descripcion TEXT,
    IN p_equipos_texto VARCHAR(255),
    IN p_detalles JSON,
    IN p_sedes JSON,

    -- Icono
    IN p_icono_nombre   VARCHAR(255),
    IN p_icono_mime     VARCHAR(100),
    IN p_icono_contenido LONGBLOB,

    -- Copa
    IN p_copa_nombre    VARCHAR(255),
    IN p_copa_mime      VARCHAR(100),
    IN p_copa_contenido LONGBLOB,

    -- Mascota
    IN p_mascota_nombre    VARCHAR(255),
    IN p_mascota_mime      VARCHAR(100),
    IN p_mascota_contenido LONGBLOB,

    IN p_creadoAdmin INT
)
BEGIN
    DECLARE v_mundial_id INT;
    DECLARE v_pais_id INT;

    DECLARE i INT DEFAULT 0;
    DECLARE j INT DEFAULT 0;

    DECLARE sedes_count INT;
    DECLARE v_nombre_pais VARCHAR(100);

    DECLARE equipo VARCHAR(100);
    DECLARE equipos_json JSON DEFAULT JSON_ARRAY();

    DECLARE equipos_cursor CURSOR FOR 
        SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(p_equipos_texto, ',', n.n), ',', -1))
        FROM (
            SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION
            SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10
        ) n
        WHERE n.n <= (LENGTH(p_equipos_texto) - LENGTH(REPLACE(p_equipos_texto, ',', '')) + 1);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET i = 9999;

    START TRANSACTION;

    -- Insertar en MUNDIAL
    INSERT INTO Mundial (anio, titulo, descripcion, equipos, detalles, CreadoAdmin)
    VALUES (p_anio, p_titulo, p_descripcion, p_equipos_texto, p_detalles, p_creadoAdmin);

    SET v_mundial_id = LAST_INSERT_ID();

    -- Procesar lista de equipos
    SET i = 0;
    OPEN equipos_cursor;

    read_loop: LOOP
        
        FETCH equipos_cursor INTO equipo;
        IF i = 9999 THEN
            LEAVE read_loop;
        END IF;

        IF i = 0 THEN
            SET equipos_json = JSON_ARRAY(equipo);
        ELSE
            SET equipos_json = JSON_ARRAY_APPEND(equipos_json, '$', equipo);
        END IF;

        SET i = i + 1;
    END LOOP;

    CLOSE equipos_cursor;

    -- Procesar sedes
    SET sedes_count = JSON_LENGTH(p_sedes);
    SET j = 0;

    WHILE j < sedes_count DO

        SET v_nombre_pais = JSON_UNQUOTE(JSON_EXTRACT(p_sedes, CONCAT('$[', j, ']')));

        SELECT id INTO v_pais_id
        FROM Pais
        WHERE pais = v_nombre_pais
        LIMIT 1;

        IF v_pais_id IS NULL THEN
            INSERT INTO Pais(pais) VALUES (v_nombre_pais);
            SET v_pais_id = LAST_INSERT_ID();
        END IF;

        INSERT INTO Sede(mundial_id, sede_id)
        VALUES (v_mundial_id, v_pais_id);

        SET j = j + 1;
    END WHILE;

    -- ICONO
    INSERT INTO Multimedia(nombre_archivo, tipo_mime, contenido)
    VALUES (p_icono_nombre, p_icono_mime, p_icono_contenido);
    SET v_pais_id = LAST_INSERT_ID();

    INSERT INTO Multimedia_mundial(multimedia_id, mundial_id, es_banner)
    VALUES (v_pais_id, v_mundial_id, 1);

    -- COPA
    INSERT INTO Multimedia(nombre_archivo, tipo_mime, contenido)
    VALUES (p_copa_nombre, p_copa_mime, p_copa_contenido);
    SET v_pais_id = LAST_INSERT_ID();

    INSERT INTO Multimedia_mundial(multimedia_id, mundial_id, es_banner)
    VALUES (v_pais_id, v_mundial_id, 0);

    -- MASCOTA
    INSERT INTO Multimedia(nombre_archivo, tipo_mime, contenido)
    VALUES (p_mascota_nombre, p_mascota_mime, p_mascota_contenido);
    SET v_pais_id = LAST_INSERT_ID();

    INSERT INTO Multimedia_mundial(multimedia_id, mundial_id, es_banner)
    VALUES (v_pais_id, v_mundial_id, 0);

    COMMIT;

    SELECT v_mundial_id AS id_mundial;

END$$

DELIMITER ;

select * from mundial;
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

ALTER TABLE Mundial ADD COLUMN detalles JSON NULL;
