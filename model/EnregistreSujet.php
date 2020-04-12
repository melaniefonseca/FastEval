<?php

require_once ("../include/PdoFastEval.php");

$pdo = PdoFastEval::getPdoFastEval();
$maxSujet =$pdo->getMaxSujet(); 

if(!empty($_POST['Valider'])) {
    $idSujet = $maxSujet + 1;
    $libelleunique = $_POST["libelle_sujet"];
    $chemin = $_POST["nv_sujet"];	
    $pdo-> insertSujet($idSujet, $libelleunique, $chemin);
}
include("../View/sujets.php");
?>


