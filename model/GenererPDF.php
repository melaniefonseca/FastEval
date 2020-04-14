<?php
require_once ("../include/PdoFastEval.php");

$pdo = PdoFastEval::getPdoFastEval();
var_dump($_POST["idsujet"]);
if (isset($_POST["idsujet"])) {
    
    $pdo = PdoFastEval::getPdoFastEval();
    $idSujet = explode("|",$_POST["idsujet"])[0];
    $idEvaluation = explode("|",$_POST["idsujet"])[1];
    
    $numSujet = $pdo->getNumSujet($idSujet);
    if ($numSujet == null) {
        $maxNumSujet = $pdo->getMaxNumSujet();
        if ($maxNumSujet == null) {
            $maxNumSujet = 0;
        }
        $numSujet = $maxNumSujet+1;
        $pdo->ajouterNumSujet($numSujet, $idSujet);
    }
    
    $cheminSujet = str_replace(" ", "", $pdo->getCheminSujet($idSujet));
    
    $nomNouveauFichier = str_replace(".txt", ".pdf", explode("/", $cheminSujet)[count(explode("/", $cheminSujet))-1]);
    
    $anonymat = $pdo->getAnonymat($idEvaluation);
    $anonyme = "true";
    if (!$anonymat) {
        $anonyme = "false";
    }
    
    // script python mise en forme
    chdir('../script/');
    $cheminScript = getcwd().'\pdf_creator.py';
    
    $reponse=shell_exec($cheminScript.' '.$cheminSujet.' '.$nomNouveauFichier.' '.$anonyme.' '.$numSujet);
    
    if (is_null($reponse)) {
        
        // script correction
        $cheminScript = getcwd().'\liste_bonnes_reponses.py';
        $correction = shell_exec($cheminScript.' '.$cheminSujet);
        
        // enregistrer correction en base
        $pdo->ajouterCorrection($correction, $idSujet);
        
        // Header content type 
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $nomNouveauFichier . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        // Read the file 
        @readfile($nomNouveauFichier);
        
        unlink($nomNouveauFichier);
    }
}
$_POST = array();
include("../View/sujets.php");
?>