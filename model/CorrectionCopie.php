
<?php
require_once ("../include/PdoFastEval.php");

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
$cheminEnregistrement="../script/resultat/result.jpg";
if ($dir = opendir("../script/imgSujet")) {
  while($file = readdir($dir)) {
    $rest = substr($file, 0,5);
    if (strcmp($rest,"copie")==0){
        $copie="..\script\imgSujet";
        $file="\\$file";
        $cheminCopie=$copie.$file;
        //$cheminCopie=$copie."copie_4.jpeg";
        //exec('C:\Users\melan\AppData\Local\Programs\Python\Python38\python C:\wamp64\www\FastEval\script\correctionCopie.py '.$tab.' '.$cheminCopie.' '.$bonne_reponse.' '.$mauvaise_reponse.' '.$absence_reponse.' '.$non_reconnaissance_reponse.' '.$cheminEnregistrement.' 2>&1', $reponse);
        //print_r($reponse);
    }
  }
  closedir($dir);
}

$cheminCopie="C:\wamp64\www\FastEval\script\sujet(1)_004.jpg";
exec('C:\Users\melan\AppData\Local\Programs\Python\Python38\python C:\wamp64\www\FastEval\script\correctionCopie.py '.$tab.' '.$cheminCopie.' '.$bonne_reponse.' '.$mauvaise_reponse.' '.$absence_reponse.' '.$non_reconnaissance_reponse.' '.$cheminEnregistrement.' 2>&1', $reponse);
print_r($reponse);

?>

