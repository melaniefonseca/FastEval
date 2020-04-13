<?php

require_once ("../include/PdoFastEval.php");

$pdo = PdoFastEval::getPdoFastEval();


if(!empty($_POST['Valider'])) {
    $id_promotion =$_POST["idpromo"];
    $numAnonymat = $_POST["numero_anonyme"];	
    $pdo-> insertEtudiantAnonyme($id_promotion,$numAnonymat);
}

include("../View/formEtudiantAnonyme.php");
?>

