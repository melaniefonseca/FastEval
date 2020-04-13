<?php
include("header.html") ;
require_once ("../include/PdoFastEval.php");
$pdo = PdoFastEval::getPdoFastEval();

$idevaluation = isset($_GET['ideval']) ? $_GET['ideval']:false;

$pdo = PdoFastEval::getPdoFastEval();
$ideval=$pdo->getID();
$numeroetudiant=$pdo-> getNumeroEtudiantByIdEvaluation($idevaluation);
$nom=$pdo->  getNomEtudiantByIdEvaluation($idevaluation);
$prenom=$pdo->  getPrenomEtudiantByIdEvaluation($idevaluation);
$note=$pdo->  getNoteEtudiantByIdEvaluation($idevaluation);
$numeroanonyme=$pdo->  getNumeroAnonymatByIdEvaluation($idevaluation);


?>
 <section class="services" id="services"> 
    <h1 class="home-title">Resultats</h1>
    <body>
    	

 <form method="get"  style="text-align: center;">
            <div class="bloc_correction">
		<br><h2 class="title-text" style="text-decoration: underline; color: black;"> CHOIX DE L'EVALUATION : </h2>
                <br><label style="font-weight: bold;"> Sélectionner une évaluation déja effectué : </label><br>
       

       <form action='/FastEval/View/resultats' method='get'>
<select name='ideval' onchange='submit();return false;'>

 <?php 
if ( $idevaluation == '') { ?>
  <option value='0'>--- Choisir ---</option> 
  <?php
}
else { ?>
  <option value = '<?php print_r($idevaluation) ?>' > Evaluation  <?php print_r($idevaluation)  ?> </option> 
  <?php
}

 ?>
  
<?php
  for ($i = 0 ; $i < sizeof($ideval) ; $i++) { 
    if ($ideval[$i][0]!=$idevaluation){
      ?>
        <option value = '<?php print_r($ideval[$i][0]) ?>' > Evaluation  <?php print_r($ideval[$i][0])  ?> </option>
      <?php
    }
  }
?>

<br>
</select>
</form>
<br>
<br>
</div>
<br>
</div>
<br>



<br><br>

<div class="bloc_correction">
		          <br><h2 class="title-text" style="text-decoration: underline; color: black;"> NOTES DES ETUDIANTS :  </h2><br>
               

<p><?php $tabl= array();

for ($l = 0 ; $l < sizeof($numeroetudiant) ; $l++) { 
	$tabl[$l][1]=$numeroetudiant[$l][0];
	$tabl[$l][2]=$nom[$l][0];
	$tabl[$l][3]=$prenom[$l][0];
	$tabl[$l][4]=$note[$l][0];
}

	$tab2= array();

for ($l = 0 ; $l < sizeof($numeroanonyme) ; $l++) { 
	$tab2[$l][1]=$numeroanonyme[$l][0];
	$tab2[$l][2]=$note[$l][0];
}
$vide=0;
if(sizeof($numeroetudiant)==0 && sizeof($numeroanonyme)==0){
	$vide=1;
}
if($vide==0){


if(sizeof($numeroetudiant)!=0){


?> </p>
 

	 	<table >

 	 	 <tr> 
 			<th> Numero Etudiant</th>
 			<th> Nom </th>
 			<th> Prénom</th>
 			<th> Note </th>
 		 </tr>

				<?php
					for ($i = 0; $i <  sizeof($numeroetudiant); $i++) {
				?>

		 <tr>
			<?php
				for ($j = 1; $j <= 4; $j++){
			?>
			<td> <?php echo $tabl[$i][$j]; ?> </td >

			<?php
				}
			?>

		 </tr>

			<?php
				}
			?>	
 		</table>
			<br>

<?php
}else{


?>

		 <table>

 		 <tr> 
 			<th> Numero Anonymat </th>
 			<th> Note </th>
		 </tr>

		<?php
			for ($i = 0; $i <  sizeof($numeroanonyme); $i++) {
		?>

		 <tr>
			<?php
			for ($j = 1; $j <= 2; $j++){
			?>
				<td> <?php echo $tab2[$i][$j]; ?> </td >

			<?php
				}
			?>
		 </tr>

			<?php
			}
			?>
 		</table>
<?php
}
}
	
?>
<br>


    </body>
 </section>
<?php
include("footer.html") ;
?>
