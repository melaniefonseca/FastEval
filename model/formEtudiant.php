<?php
require_once ("../include/PdoFastEval.php");
include '../Excel//PHPExcel/IOFactory.php';
$pdo = PdoFastEval::getPdoFastEval();

$Anonyme = $_POST['Anonyme'];
if($Anonyme==-1){
    include('../view/etudiants.php');
}
if(isset($_POST['form']) AND $_POST['form']=='Créer les étudiants via un formulaire'){
    if($Anonyme==0){
        include('../view/formEtudiantNominatif.php');
    }
    else{
        include('../view/formEtudiantAnonyme.php');
    }
}
else{
    if(isset($_POST['xls']) AND $_POST['xls']=='Créer les étudiants via un ficher xls'){
        $idpromotion=$_POST['idpromo'];
        $inputFileName = '../Excel/test.xls';
        try
        {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        }
        catch(Exception $e)
        {
            echo "fichier introuvable";
            exit();
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        for ($row = 1; $row <= $highestRow; $row++)
        {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                NULL,
                TRUE,
                FALSE);

            $rowData = $rowData[0];
            if($Anonyme==0){
                $numEtudiant=trim($rowData[0]);
                $prenom=trim($rowData[1]);
                $nom=trim($rowData[2]);
                $pdo->insertEtudiantNominatif($idpromotion,$numEtudiant,$prenom, $nom);
            }else{
                $numAnonymat=trim($rowData[0]);
                $pdo->insertEtudiantAnonyme($idpromotion,$numAnonymat);
                
            }
        }
        include('../view/etudiants.php');
    }
}
?>

