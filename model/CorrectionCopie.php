
<?php
require_once ("../include/PdoFastEval.php");
require('../pdf/pdf.php');


$pdo = PdoFastEval::getPdoFastEval();
$bonne_reponse=$pdo->getBareme("bonne_reponse");
$mauvaise_reponse=$pdo->getBareme("mauvaise_reponse");
$absence_reponse=$pdo->getBareme("absence_reponse");
$non_reconnaissance_reponse=$pdo->getBareme("non_reconnaissance_reponse");

if(isset($_FILES['Import/scanne'])){
    $errors= array();
    $file_name = $_FILES['Import/scanne']['name'];
    $file_size =$_FILES['image']['size'];
    $file_tmp =$_FILES['Import/scanne']['tmp_name'];
    $file_type=$_FILES['Import/scanne']['type'];
}

//$sujet = $_POST['Import/scanne'];
$lstCopie = "C:\wamp64\www\FastEval\script\sujet2.pdf";
$cheminImg="../script/imgSujet";
exec('C:\Users\melan\AppData\Local\Programs\Python\Python38\python C:\wamp64\www\FastEval\script\pdfToImg.py '.$lstCopie.' '.$cheminImg.' 2>&1',$rep);
//print_r($rep);
$tab="0,2;2;2;0;1;1,2;1;2;1,2;2,1;2;1;0;";
$nbQuestion=0;
for ($i=0;$i<strlen($tab);$i++) {
  if (strcmp($tab[$i],";")==0) {
      $nbQuestion+=1;
  }
}
$anonyme=0;
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
        
        /*if($reponse!=""){
            for ($i=0;$i<strlen($reponse[0]);$i++) {
                if (strcmp($reponse[0][$i],'/')==0) {
                    $questionCorriger=(int)$reponse[0][$i+1].(int)$reponse[0][$i+2];
                }
            }
        }*/
        
        $cheminImgNum="../script/NumEtudiant/numEtudiant.png";
        exec('C:\Users\melan\AppData\Local\Programs\Python\Python38\python C:\wamp64\www\FastEval\script\numEtudiant.py '.$cheminCopie.' '.$cheminImgNum.' 2>&1', $numEtudiantScript);
        //print_r((int)$numEtudiantScript[0]);
        
        $cheminCopie="..\script\sujet(1)_004.jpg";
        exec('C:\Users\melan\AppData\Local\Programs\Python\Python38\python C:\wamp64\www\FastEval\script\correctionCopie.py '.$tab.' '.$cheminCopie.' '.$bonne_reponse.' '.$mauvaise_reponse.' '.$absence_reponse.' '.$non_reconnaissance_reponse.' '.$cheminEnregistrement.' 2>&1', $reponse);
        //print_r($reponse[0]);
        
        if((int)$reponse[0]!=-1){
            if(strcmp($numEtudiantScript[0],$numEtudiant)==0 or $numEtudiant==0){
                $numEtudiant=$numEtudiantScript[0];
                $j=0;
                $noteString="";
                while($j<strlen($reponse[0]) && strcmp($reponse[0][$j],'/')!=0){
                     //print_r(substr($reponse[0][$j], 0, 4));
                    //print_r($reponse[0][$j]);
                    //$noteString+=$reponse[0][$j];
                    $j++;
                }
                $note+=(float)$noteString;
            }
            else{
                $idpromotion="";
                $idEtudiant="";
                //faire l'enregistrement
                if($anonyme=0){
                    $idEtudiant=$pdo->getIdEtudiantByNumeroEtudiant($numEtudiant);
                }
                else{
                    $idEtudiant=$pdo->getIdEtudiantByNumeroAnonymat($numEtudiant);
                }
                $idpromotion=getIdPromotionByEtudiant($idEtudiant);
                $note=0;
                $numEtudiant=$numEtudiantScript[0];
            }
            
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->Image('../script/resultat/result.jpg',9,20,50);
            $pdf->Image('../script/NumEtudiant/numEtudiant.png',130,40,70);
        }  
    }
  }
  closedir($dir);
}
$pdf->Output("copieCorriger.pdf", "F");
$pdf->Output();



?>

