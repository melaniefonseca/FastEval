<?php
include("header.html") ;
require_once ("../include/PdoFastEval.php");


$pdo = PdoFastEval::getPdoFastEval();
$libelle=$pdo->getLibelle();
$id=$pdo->getIdSujet();
$n=$pdo->getNombresIdSujet();
$maxSujet =$pdo->getMaxSujet(); 

 
?>
 <section class="services" id="services"> 
    <h1 class="home-title">SUJETS</h1>
	<body>
	
<BR>
<div class="bloc_correction">

<br><h2 class="title-text" style="text-decoration: underline; color: black;"> IMPORTATION DES SUJETS : </h2>
 <br> <label style="font-weight: bold; text-align: center"> Importer un nouveau sujet : </label> 
		              <div class="centre_blocTexte">
		              	<form method="post" action="../model/EnregistreSujet.php" >
                     		<br> <label > Libelle : </label> 
                    		 <input type ="text" name ="libelle_sujet" value="" >
                    		 <label > Fichier : </label> <br>
                   			 <input type="file" name="nv_sujet" id="nv_sujet" accept=".pdf" value="" multiple=""><br><br>
                    		<input class="bouton_valider" type="submit" id="Valider" name="Valider" value="Valider" ><br><br>
                		</form>
                     </div>
</div>



<br><br>

<div class="bloc_correction">
		          <br><h2 class="title-text" style="text-decoration: underline; color: black;"> LISTE DES SUJETS GENERES :  </h2><br>
               

<p><?php $tabl= array();

for ($l = 0 ; $l < sizeof($id) ; $l++) { 
	$tabl[$l][1]=$id[$l][0];
	$tabl[$l][2]=$libelle[$l][0];
}


?> </p>


 <table >

 	<tr> 
 		<th> Numero </th>
 		<th> Nom du sujet </th>

 	</tr>
<?php

for ($i = 0; $i < $n; $i++) {
	?>

	<tr>
	<?php
	for ($j = 1; $j <= 2; $j++){
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
            </div>
	</body>
</section>

<?php
include("footer.html") ;
?>