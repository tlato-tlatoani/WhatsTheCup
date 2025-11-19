DROP TRIGGER IF EXISTS tr_publicacion_before_update;

DELIMITER $$

CREATE TRIGGER tr_publicacion_before_update
BEFORE UPDATE ON PUBLICACION
FOR EACH ROW
BEGIN
    -- Solo actuar si cambia la aprobación
    IF NEW.AprobadoAdmin <> OLD.AprobadoAdmin THEN
        
        -- Establecer fecha
        SET NEW.FECHA_APROBACION = NOW();

        -- Estatus según aprobación
        IF NEW.AprobadoAdmin = 1 THEN
            SET NEW.ESTATUS = 'aprobada';
        ELSEIF NEW.AprobadoAdmin = -1 THEN
            SET NEW.ESTATUS = 'rechazada';
        END IF;

    END IF;
END$$

DELIMITER ;
