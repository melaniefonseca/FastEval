<?php
require_once ("../include/PdoFastEval.php");


if (isset($_POST["dateEvaluation"]) && isset($_POST["CC_EX"]) && isset($_POST["nbEtudiants"]) && isset($_POST["idpromotion"])) {
    
    $pdo = PdoFastEval::getPdoFastEval();
    $bonne_reponse=$pdo->getBareme("bonne_reponse");
    $mauvaise_reponse=$pdo->getBareme("mauvaise_reponse");
    $absence_reponse=$pdo->getBareme("absence_reponse");
    $non_reconnaissance_reponse=$pdo->getBareme("non_reconnaissance_reponse");
    
    $id_bareme=$pdo->getMaxBareme();
    $nbEtudiant = $_POST["nbEtudiants"];
    $dateEval=$_POST['dateEvaluation'];
    $typeEval=$_POST['CC_EX'];
    
    $pdo->insertEvaluation($typeEval, $nbEtudiant, $dateEval, $id_bareme);
    
}
$_POST = array();
include("../View/sujets.php");
?>