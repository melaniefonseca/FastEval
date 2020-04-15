<?php

require_once ("../include/PdoFastEval.php");

$pdo = PdoFastEval::getPdoFastEval();

if(!empty($_POST['Valider'])) {
    $id_promotion = $_POST["idpromo"];
    $numEtudiant = $_POST["numero_etudiant"];	
	$nom = $_POST["nom"];
    $prenom = $_POST["prenom"];	
    $pdo-> insertEtudiantNominatif($id_promotion, $numEtudiant, $nom, $prenom);
}
include("../View/formEtudiantNominatif.php");
?>
