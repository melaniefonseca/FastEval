<?php

require_once ("../include/PdoFastEval.php");

$pdo = PdoFastEval::getPdoFastEval();

if(!empty($_POST['Valider'])) {
	$nom_promotion = $_POST["nom_promotion"];
    $date_promotion = $_POST["date_promotion"];	
    $pdo-> insertPromotion($nom_promotion, $date_promotion);
}

include("../View/etudiants.php");
?>
