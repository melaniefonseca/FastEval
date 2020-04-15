<?php
require('fpdf.php');
/*
   Class: PDF
   Auteur : FONSECA NASCIMENTO Mélanie
   Crée le : 05/04/2020
   Mis a jour le : 12/04/2020
*/
class PDF extends FPDF
{
// En-tête
function Header()
{
    // Logo
    $this->Image('../content/img/Logo_FastEval.png',9,10,20);
    // Police Arial gras 15
    $this->SetFont('Arial','B',15);
    // Décalage à droite
    $this->Cell(60);
    // Titre
    $this->Cell(65,10,'Feuille de reponses',1,0,'C');
    // Saut de ligne
    $this->Ln(20);
}

// Pied de page
function Footer()
{
    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',8);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}

}
