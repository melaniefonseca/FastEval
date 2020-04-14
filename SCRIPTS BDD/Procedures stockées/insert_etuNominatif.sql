Create or replace function insert_etuNominatif(in pid_promotion integer, in pnum_etudiant text, in pnom_etudiant text, in pprenom_etudiant) returns text as $$
declare
Id_Etu text;
begin

	INSERT INTO etudiant (id_promotion) 
	VALUES (pid_promotion);
	
	SELECT max(id_etudiant) into Id_Etu FROM etudiant;

	INSERT INTO etudiant_nominatif (id_etudiant, id_promotion, num_etudiant, nom_etudiant, prenom_etudiant) 
	VALUES (Id_Etu, pid_promotion, pnum_etudiant, pnom_etudiant, pprenom_etudiant);
	
	begin
	select id_etudiant into Id_Etu
	 from etudiant_nominatif where etudiant_nominatif.id_etudiant=Id_Etu;
	 	
	end;
	return Id_Etu;
end;
$$  LANGUAGE plpgsql


-- Procedure pour phpMyAdmin
DELIMITER |
CREATE FUNCTION insert_etuNominatif (pid_promotion int, pnum_etudiant char(30), pnom_etudiant char(30), pprenom_etudiant char(30))  RETURNS CHAR(30)
BEGIN
    DECLARE Id_Etu CHAR(30);
	
	INSERT INTO etudiant (id_promotion) 
	VALUES (pid_promotion);
	
	SELECT max(id_etudiant) into Id_Etu FROM etudiant;

	INSERT INTO etudiant_nominatif (id_etudiant, id_promotion, num_etudiant, nom_etudiant, prenom_etudiant) 
	VALUES (Id_Etu, pid_promotion, pnum_etudiant, pnom_etudiant, pprenom_etudiant);
	
    select id_etudiant into Id_Etu
	 from etudiant_nominatif where etudiant_nominatif.id_etudiant=Id_Etu;
	 
    RETURN Id_Etu;
END;
    |
	
	
-- Supprimer la requête
	
DROP FUNCTION IF EXISTS `insert_etuNominatif`;