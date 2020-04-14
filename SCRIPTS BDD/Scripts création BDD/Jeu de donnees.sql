INSERT INTO `promotion`( `NOM_PROMOTION`, `DATE_PROMOTION`) VALUES ('L3 MIAGE','2019-01-01');
INSERT INTO `promotion`( `NOM_PROMOTION`, `DATE_PROMOTION`) VALUES ('M1 MIAGE','2019-01-01');
INSERT INTO `promotion`( `NOM_PROMOTION`, `DATE_PROMOTION`) VALUES ('M2 MIAGE','2019-01-01');

Select `insert_etuNominatif` (1,'23459856', 'Dumas', 'Louise') ; 
Select `insert_etuNominatif` (1,'84675923', 'Lacroix', 'Gabriel') ; 
Select `insert_etuNominatif` (1,'56283942', 'Perrier', 'Camille') ; 
Select `insert_etuNominatif` (1,'11324567', 'Marchal', 'Arthur') ; 
Select `insert_etuNominatif` (1,'12456784', 'Rey', 'Paul') ; 

Select `insert_etuNominatif` (2,'89065432', 'Taylor', 'Sarah') ; 
Select `insert_etuNominatif` (2,'34678932', 'Monnier', 'Antoine') ; 
Select `insert_etuNominatif` (2,'35674647', 'Dupuy', 'Raphael') ; 
Select `insert_etuNominatif` (2,'56784543', 'Gillet', 'Chloe') ; 
Select `insert_etuNominatif` (2,'44567888', 'Huet', 'Inés') ; 

Select `insert_etuNominatif` (3,'44455367', 'Perez', 'Brian') ; 
Select `insert_etuNominatif` (3,'33456789', 'Denis', 'Agathe') ; 
Select `insert_etuNominatif` (3,'98976534', 'Lamy', 'Hugo') ; 
Select `insert_etuNominatif` (3,'67845678', 'Carlier', 'Leo') ; 
Select `insert_etuNominatif` (3,'46656744', 'Bertin', 'Yanis') ; 

Select `insert_etuAnonyme` (1,'156712') ; 
Select `insert_etuAnonyme` (1,'156713') ; 
Select `insert_etuAnonyme` (1,'1567124') ; 
Select `insert_etuAnonyme` (1,'1567125') ; 
Select `insert_etuAnonyme` (1,'1567126') ; 

Select `insert_etuAnonyme` (2,'4567813') ; 
Select `insert_etuAnonyme` (2,'4567814') ; 
Select `insert_etuAnonyme` (2,'4567815') ; 
Select `insert_etuAnonyme` (2,'4567816') ; 
Select `insert_etuAnonyme` (2,'4567817') ; 

Select `insert_etuAnonyme` (3,'6756231') ; 
Select `insert_etuAnonyme` (3,'6756232') ; 
Select `insert_etuAnonyme` (3,'6756233') ; 
Select `insert_etuAnonyme` (3,'6756234') ; 
Select `insert_etuAnonyme` (3,'6756235') ; 

insert into `bareme` (`BONNE_REP`, `MAUVAISE_REP`, `ABSENCE_REP`, `NON_RECONNAISSANCE_REP`) values  (1, -1, 0, 0)

INSERT INTO `evaluation`( `TYPE`, `NB_ETUDIANT`, `DATE_EVALUATION`, `ID_BAREME`) VALUES ('CC',41, '2019-12-13', 1);

INSERT INTO `sujet`( `LIBELLE`, `CHEMIN`, `ID_EVALUATION`) VALUES ('Sujet_exam_2019_M1','C:\wamp64\www\FastEval\Sujets\Sujet_exam_2019_M1', 1);


INSERT INTO `note`( `ID_EVALUATION`, `ID_ETUDIANT`, `ID_PROMOTION`, `NOTE`) VALUES (1,1,2,12);
INSERT INTO `note`( `ID_EVALUATION`, `ID_ETUDIANT`, `ID_PROMOTION`, `NOTE`) VALUES (1,2,2,14);
INSERT INTO `note`( `ID_EVALUATION`, `ID_ETUDIANT`, `ID_PROMOTION`, `NOTE`) VALUES (1,3,2,9);
INSERT INTO `note`( `ID_EVALUATION`, `ID_ETUDIANT`, `ID_PROMOTION`, `NOTE`) VALUES (1,4,2,10);
INSERT INTO `note`( `ID_EVALUATION`, `ID_ETUDIANT`, `ID_PROMOTION`, `NOTE`) VALUES (1,5,2,11);




