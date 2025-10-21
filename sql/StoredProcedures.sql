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