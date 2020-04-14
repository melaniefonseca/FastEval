Create or replace function delete_etuAnonyme(in pid_etudiant integer) returns void as $$
begin
	delete from etudiant_anonyme
	where etudiant_anonyme.id_etudiant=pid_etudiant;
	
	delete from etudiant
	where etudiant.id_etudiant=pid_etudiant;
	
end;
$$  LANGUAGE plpgsql

-- Procedure pour phpMyAdmin
DELIMITER |
CREATE FUNCTION delete_etuAnonyme (pid_etudiant int)  RETURNS int
BEGIN
	
	delete from etudiant_anonyme
	where etudiant_anonyme.id_etudiant=pid_etudiant;
	
	delete from etudiant
	where etudiant.id_etudiant=pid_etudiant;
	
	RETURN pid_etudiant;
END;
    |
	
-- Supprimer la procédure
	
DROP FUNCTION IF EXISTS `delete_etuAnonyme`;