<?php
$bonne_rep = $_POST['bonne_reponse']; 
$mauvaise_rep = $_POST['mauvaise_reponse']; 
$absence_rep = $_POST['absence_reponse'];
$non_reco_rep = $_POST['non_reconnaissance_reponse'];
$stockage_sujet = $_POST['stockage_sujet'];
$stockage_copies = $_POST['stockage_copies_corrige'];
require_once ("../include/PdoFastEval.php");

$pdo = PdoFastEval::getPdoFastEval();
$pdo->majParametre('bonne_reponse',$bonne_rep);
$pdo->majParametre('mauvaise_reponse',$mauvaise_rep);
$pdo->majParametre('absence_reponse',$absence_rep);
$pdo->majParametre('non_reconnaissance_reponse',$non_reco_rep);
$pdo->majParametre('stockage_sujet',$stockage_sujet);
$pdo->majParametre('stockage_copies_corrige',$stockage_copies);
include("../View/parametre.php");
?>


