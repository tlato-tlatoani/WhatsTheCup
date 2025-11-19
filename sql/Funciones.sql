DELIMITER $$

CREATE FUNCTION fn_contar_likes(p_publicacion_id INT)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total
    FROM interaccion
    WHERE publicacion_id = p_publicacion_id;
    RETURN total;
END$$

DELIMITER ;
