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
