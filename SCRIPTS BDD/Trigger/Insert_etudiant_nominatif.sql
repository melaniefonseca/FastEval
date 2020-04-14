Create or replace  function verif_etudiant()
returns trigger AS $$

begin
	
	DECLARE Id_Etu CHAR(30);
	
	SELECT COUNT(*) INTO Id_Etu
	FROM Etudiant E
	LEFT JOIN etudiant_anonyme EA ON E.id_etudiant = EA.id_etudiant
	LEFT JOIN etudiant_nominatif EN ON E.id_etudiant = EN.id_etudiant 
	WHERE E.id_etudiant = NEW.id_etudiant ;

	IF Id_Etu > 0 THEN
        RAISE EXCEPTION 'etudiant déjà affectée (etudiant_nominatif)';
	END IF ;
	
	return new;
end;
$$  LANGUAGE plpgsql;


drop trigger Insert_etudiant_nominatif on etudiant_nominatif;

create  trigger Insert_etudiant_nominatif BEFORE INSERT
on etudiant_nominatif for each row EXECUTE PROCEDURE verif_etudiant();


-- TRIGGER pour phpMyAdmin
DELIMITER |
CREATE TRIGGER Insert_etudiant_nominatif BEFORE INSERT ON etudiant_nominatif FOR EACH ROW
BEGIN
	DECLARE Id_Etu CHAR(30);
	DECLARE msg varchar(128);
	
	SELECT COUNT(*) INTO Id_Etu
	FROM Etudiant E
	LEFT JOIN etudiant_anonyme EA ON E.id_etudiant = EA.id_etudiant
	LEFT JOIN etudiant_nominatif EN ON E.id_etudiant = EN.id_etudiant 
	WHERE E.id_etudiant = NEW.id_etudiant ;

	IF Id_Etu > 0 THEN
		set msg = concat('etudiant déjà affectée (etudiant_nominatif)', cast(new.id_etudiant as char));
        signal sqlstate '45000' set message_text = msg;
	END IF ;
END ;
    |

