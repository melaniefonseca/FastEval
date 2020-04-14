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
        RAISE EXCEPTION 'etudiant déjà affectée (etudiant_anonyme)';
	END IF ;
	
	return new;
end;
$$  LANGUAGE plpgsql;


drop trigger Insert_etudiant_anonyme on etudiant_anonyme;

create  trigger Insert_etudiant_anonyme BEFORE INSERT
on etudiant_anonyme for each row EXECUTE PROCEDURE verif_etudiant();


-- TRIGGER pour phpMyAdmin
DELIMITER |
CREATE TRIGGER Insert_etudiant_anonyme BEFORE INSERT ON etudiant_anonyme FOR EACH ROW
BEGIN
	DECLARE Id_Etu CHAR(30);
	DECLARE msg varchar(128);
	
	SELECT COUNT(*) INTO Id_Etu
	FROM Etudiant E
	LEFT JOIN etudiant_anonyme EA ON E.id_etudiant = EA.id_etudiant
	LEFT JOIN etudiant_nominatif EN ON E.id_etudiant = EN.id_etudiant 
	WHERE E.id_etudiant = NEW.id_etudiant ;

	IF Id_Etu > 0 THEN
		set msg = concat('etudiant déjà affectée (etudiant_anonyme)', cast(new.id_etudiant as char));
        signal sqlstate '45000' set message_text = msg;
	END IF ;
END ;
    |

