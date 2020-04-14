Create or replace  function delete_etudiant()
returns trigger AS $$

begin
	
	delete from etudiant where id_etudiant=old.id_etudiant;
	
	return old;
end;
$$  LANGUAGE plpgsql;


drop trigger delete_etudiant_anonyme on etudiant_anonyme;

create  trigger delete_etudiant_anonyme after delete 
on etudiant_anonyme for each row EXECUTE PROCEDURE delete_etudiant();


-- TRIGGER pour phpMyAdmin
DELIMITER |
CREATE TRIGGER delete_etudiant_anonyme AFTER DELETE ON etudiant_anonyme
FOR EACH ROW 
BEGIN
	
	DELETE FROM etudiant WHERE id_etudiant=old.id_etudiant;
	
END;
    |