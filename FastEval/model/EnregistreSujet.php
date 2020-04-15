<?php

require_once ("../include/PdoFastEval.php");

$pdo = PdoFastEval::getPdoFastEval();

if(isset($_POST["libelle_sujet"]) && isset($_FILES["sujet"]) && isset($_POST["idevaluation"])) {
    
    $idEvaluation = $_POST["idevaluation"];
    $libelleunique = str_replace(" ", "", $_POST["libelle_sujet"]);
    
    chdir('../Sujets/');
    
    if(isset($_FILES['sujet'])){
        
        if ($libelleunique == "") {
            $_POST["erreurSujet"] = "Libellé manquant";
        }
        else {
            $dossier = getcwd().'/';
            $fichier = basename($_FILES['sujet']['name']);
            
            $fichierExiste = file_exists($dossier . $libelleunique.'.txt');
            
            if (!$fichierExiste) {
                move_uploaded_file($_FILES['sujet']['tmp_name'], $dossier . $libelleunique.'.txt');
                $chemin = str_replace("\\", "/", getcwd() . "\\" . $libelleunique . ".txt");
                
                $pdo->insertSujet($libelleunique, $chemin, $idEvaluation);
            }
        }
    }
}

$_POST = array();
include("../View/sujets.php");
?>