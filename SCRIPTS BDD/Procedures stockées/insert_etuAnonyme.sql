Create or replace function insert_etuAnonyme(in pid_promotion integer, in pnum_anonymat text) returns text as $$
declare
Id_Etu text;
begin

	INSERT INTO etudiant (id_promotion) 
	VALUES (pid_promotion);
	
	SELECT max(id_etudiant) into Id_Etu FROM etudiant;

	INSERT INTO etudiant_anonyme (id_etudiant, id_promotion, num_anonymat) 
	VALUES (Id_Etu, pid_promotion, pnum_anonymat);
	
	begin
	select id_etudiant into Id_Etu
	 from etudiant_anonyme where etudiant_anonyme.id_etudiant=Id_Etu;
	 	
	end;
	return Id_Etu;
end;
$$  LANGUAGE plpgsql


-- Procedure pour phpMyAdmin
DELIMITER |
CREATE FUNCTION insert_etuAnonyme (pid_promotion int, pnum_anonymat char(30))  RETURNS CHAR(30)
BEGIN
    DECLARE Id_Etu CHAR(30);
	
	INSERT INTO etudiant (id_promotion) 
	VALUES (pid_promotion);
	
	SELECT max(id_etudiant) into Id_Etu FROM etudiant;

	INSERT INTO etudiant_anonyme (id_etudiant, id_promotion, num_anonymat) 
	VALUES (Id_Etu, pid_promotion, pnum_anonymat);
	
	begin
	select id_etudiant into Id_Etu
	 from etudiant_anonyme where etudiant_anonyme.id_etudiant=Id_Etu;
	 	
	end;
	 
    RETURN Id_Etu;
END;
    |
	
	
-- Supprimer la procédure
	
DROP FUNCTION IF EXISTS `insert_etuAnonyme`;