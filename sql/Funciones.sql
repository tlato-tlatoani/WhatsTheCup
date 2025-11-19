drop function if exists fn_contar_likes;
DELIMITER $$

CREATE FUNCTION fn_contar_likes(p_publicacion_id INT)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total
    FROM interacciones
    WHERE publicacion_id = p_publicacion_id;
    RETURN total;
END$$

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
