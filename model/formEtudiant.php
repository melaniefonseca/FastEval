<?php
require_once ("../include/PdoFastEval.php");
include '../Excel//PHPExcel/IOFactory.php';
$pdo = PdoFastEval::getPdoFastEval();

$Anonyme = $_POST['Anonyme'];
$AnonymeExcel = $_POST['AnonymeExcel'];
if($Anonyme==-1 && $AnonymeExcel==-1){
    include('../view/etudiants.php');
}
if(isset($_POST['form']) AND $_POST['form']=='Créer les étudiants via un formulaire'){
    if($Anonyme==0){
        include('../view/formEtudiantNominatif.php');
    }
    else{
        if($Anonyme==1){
            include('../view/formEtudiantAnonyme.php');
        }else{
            include('../view/etudiants.php');
        }
    }
}
else{
    if(isset($_POST['xls']) AND $_POST['xls']=='Créer les étudiants via un ficher xls'){
        if(!empty($_POST['xls']) && $AnonymeExcel!=-1) {
            $libelleunique = "listeEtudiants";
            chdir('../Excel/');
            
            if(isset($_FILES['fichierxls'])){
                
                $dossier = getcwd().'/';
                $fichier = basename($_FILES['fichierxls']['name']);

                $fichierExiste = file_exists($dossier . $libelleunique.'.xls');
                //if (!$fichierExiste) {
                    
                    move_uploaded_file($_FILES['fichierxls']['tmp_name'], $dossier . $libelleunique.'.xls');
                    $chemin = getcwd() . "\\" . $libelleunique . ".xls";
                    
                    $idpromotion=$_POST['idpromo'];
                    $inputFileName = '../Excel/listeEtudiants.xls';
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
                        if($AnonymeExcel==0){
                            $numEtudiant=trim($rowData[0]);
                            $prenom=trim($rowData[1]);
                            $nom=trim($rowData[2]);
                            $pdo->insertEtudiantNominatif($idpromotion,$numEtudiant,$prenom, $nom);
                        }else{
                            $numAnonymat=trim($rowData[0]);
                            $pdo->insertEtudiantAnonyme($idpromotion,$numAnonymat);

                        }
                    }
                //} 
            }
        }
        unset($_FILES);
        include('../view/etudiants.php');
    }
}
?>

