
<?php
require_once ("../include/PdoFastEval.php");
require('../pdf/pdf.php');

if(!empty($_POST['Valider'])) {
    $libelleunique = "copies";
    chdir('../script/');

    if(isset($_FILES['Import/scanne'])){

        $dossier = getcwd().'/';
        $fichier = basename($_FILES['Import/scanne']['name']);

        $fichierExiste = file_exists($dossier . $libelleunique.'.pdf');

        if (!$fichierExiste) {
            move_uploaded_file($_FILES['Import/scanne']['tmp_name'], $dossier . $libelleunique.'.pdf');
            $chemin = getcwd() . "\\" . $libelleunique . ".pdf";
        } 
    }
}
unset($_FILES);

$pdo = PdoFastEval::getPdoFastEval();
$idSujet=$_POST['idsujet'];
$lstCopie = "..\script\copies.pdf";
$idpromotion=$_POST['idpromotion'];
$idEval=$pdo->getIdEvalByIdSujet($idSujet);
$typeEval=$pdo->getTypeEvalByIDEval($idEval);
//recuperer dans bareme en fonction de l'evaluation
$bonne_reponse=$pdo->getBaremeBonneRepByIDEvaluation($idEval);
$mauvaise_reponse=$pdo->getBaremeMauvaiseRepByIDEvaluation($idEval);
$absence_reponse=$pdo->getBaremeAbsencesRepByIDEvaluation($idEval);
$non_reconnaissance_reponse=$pdo->getBaremeNonRecoRepByIDEvaluation($idEval);


if($idSujet==-1 or $idpromotion=="-1"){
    include('../view/correction.php');
}else{
    //$pdo->insertBareme($bonne_reponse, $mauvaise_reponse, $absence_reponse, $non_reconnaissance_reponse);
    //$id_bareme=$pdo->getMaxBareme();
    //$nbEtudiant=0;
    //$pdo->insertEvaluation($typeEval, $nbEtudiant, $dateEval, $idSujet,$id_bareme);
    //$idEval=$pdo->getMaxEvaluation();
    //$lstCopie = "C:\wamp64\www\FastEval\script\sujet2.pdf";
    $cheminImg="../script/imgSujet";
    exec('C:\Users\melan\AppData\Local\Programs\Python\Python38\python C:\wamp64\www\FastEval\script\pdfToImg.py '.$lstCopie.' '.$cheminImg.' 2>&1',$rep);
    //print_r($rep);
    $tab="0,2;2;2;0;1;1,2;1;2;1,2;2,1;2;1;0;";
    $tabCorrectionInit=$tab;
    $nbQuestion=0;
    for ($i=0;$i<strlen($tab);$i++) {
      if (strcmp($tab[$i],";")==0) {
          $nbQuestion+=1;
      }
    }
    $questionCorriger=0;
    $numEtudiant=0;
    $note=0;
    $pdf = new PDF();
    //$reponse[0]="";
    $cheminEnregistrement="../script/resultat/result.jpg";
    if ($dir = opendir("../script/imgSujet")) {
        while($file = readdir($dir)) {
            $rest = substr($file, 0,5);
            if (strcmp($rest,"copie")==0){
                $copie="..\script\imgSujet";
                $file="\\$file";
                $cheminCopie=$copie.$file;

                $cheminCopie=$copie."copie_4.jpeg";

                $cheminCopie="C:\wamp64\www\FastEval\script\sujet(1)_004.jpg";

                $cheminImgNum="../script/NumEtudiant/numEtudiant.png";
                exec('C:\Users\melan\AppData\Local\Programs\Python\Python38\python C:\wamp64\www\FastEval\script\numEtudiant.py '.$cheminCopie.' '.$cheminImgNum.' 2>&1', $numEtudiantScript);
                //print_r((int)$numEtudiantScript[0]." ");
                if($numEtudiant==0){
                    $numEtudiant=(int)$numEtudiantScript[0];
                    //$nbEtudiant+=1;
                    //print_r($numEtudiant);
                }

                $cheminCopie="..\script\sujet(1)_004.jpg";
                exec('C:\Users\melan\AppData\Local\Programs\Python\Python38\python C:\wamp64\www\FastEval\script\correctionCopie.py '.$tab.' '.$cheminCopie.' '.$bonne_reponse.' '.$mauvaise_reponse.' '.$absence_reponse.' '.$non_reconnaissance_reponse.' '.$cheminEnregistrement.' 2>&1', $reponse);
                //print_r($reponse[0]);

                if((int)$reponse[0]!=-1){
                    if($numEtudiantScript[0]==$numEtudiant && $questionCorriger!=$nbQuestion){
                        $j=0;
                        $noteString="";
                        while($j<strlen($reponse[0]) && strcmp($reponse[0][$j],'/')!=0){
                            $noteString=$noteString.$reponse[0][$j];
                            $j++;
                        }
                        $note+=(float)$noteString;
                        //print_r($note." ");

                        if($reponse!=""){
                            $questionCorrigerScript=0;
                            for ($i=0;$i<strlen($reponse[0]);$i++) {
                                if (strcmp($reponse[0][$i],'/')==0) {
                                    $questionCorrigerScript=(int)$reponse[0][$i+1].(int)$reponse[0][$i+2];
                                }
                            }
                        }
                        $questionCorriger+=(int)$questionCorrigerScript;
                        $nbQuestionCorriger=0;
                        if($questionCorriger<$nbQuestion){
                            for ($i=0;$i<strlen($tab);$i++) {
                                if($nbQuestionCorriger==$questionCorriger){
                                    $tab = substr($tab, $i);
                                }
                                else{
                                    if (strcmp($tab[$i],";")==0) {
                                    $nbQuestionCorriger+=1;
                                    }
                                }
                            }
                        }
                    }
                    else{
                        $tab=$tabCorrectionInit;
                        $idEtudiant="";
                        //$nbEtudiant+=1;
                        if(strcmp($typeEval,"CC"==0)){
                            $idEtudiant=$pdo->getIdEtudiantByNumeroEtudiant($numEtudiant);
                            if($idEtudiant==null){
                                $pdo->insertEtudiantNominatif($idpromotion,$numEtudiant,"","");
                                $idEtudiant=$pdo->getIdEtudiantByNumeroEtudiant($numEtudiant);
                            }
                        }
                        else{
                            $idEtudiant=$pdo->getIdEtudiantByNumeroAnonymat($numEtudiant);
                            if($idEtudiant==null){
                                $pdo->insertEtudiantAnonyme($idpromotion,$numEtudiant);
                                $idEtudiant=$pdo->getIdEtudiantByNumeroEtudiant($numEtudiant);
                            }
                        }
                        //$idpromotion=getIdPromotionByEtudiant($idEtudiant);
                        $pdo->insertNote($idEval, $idEtudiant, $idpromotion, $note);
                        //faire l'enregistrement voir pout l'evaluation
                        $note=0;
                        $numEtudiant=(int)$numEtudiantScript[0];
                    }

                  $pdf->AliasNbPages();
                  $pdf->AddPage();
                  $pdf->Image('../script/resultat/result.jpg',9,20,50);
                  $pdf->Image('../script/NumEtudiant/numEtudiant.png',130,40,70);
              }  
          }
        }
        closedir($dir);
        //print_r($nbEtudiant);
        //$pdo->updateNbEtudiantEvaluation($idEval, $nbEtudiant);
        $pdf->Output("copieCorriger.pdf", "F");
        $pdf->Output();
    }
    if ($dir = opendir("../script/imgSujet")) {
        while($file = readdir($dir)) {
            $rest = substr($file, 0,5);
            if (strcmp($rest,"copie")==0){
                $copie="..\script\imgSujet";
                $file="\\$file";
                $cheminCopie=$copie.$file;
                unlink($cheminCopie);
            }
        }
    }
}

?>

