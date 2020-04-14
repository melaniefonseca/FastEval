Create or replace  function delete_etudiant()
returns trigger AS $$

begin
	
	delete from etudiant where id_etudiant=old.id_etudiant;
	
	return old;
end;
$$  LANGUAGE plpgsql;


drop trigger delete_etudiant_nominatif on etudiant_nominatif;

create  trigger delete_etudiant_nominatif after delete 
on etudiant_nominatif for each row EXECUTE PROCEDURE delete_etudiant();


-- TRIGGER pour phpMyAdmin
DELIMITER |
CREATE TRIGGER delete_etudiant_nominatif AFTER DELETE ON etudiant_nominatif
FOR EACH ROW 
BEGIN
	
	DELETE FROM etudiant WHERE id_etudiant=old.id_etudiant;
	
END;
    |