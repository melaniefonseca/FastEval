<?php
include("header.html") ;
require_once ("../include/PdoFastEval.php");

$pdo = PdoFastEval::getPdoFastEval();
$bonne_reponse=$pdo->getBareme("bonne_reponse");
$mauvaise_reponse=$pdo->getBareme("mauvaise_reponse");
$absence_reponse=$pdo->getBareme("absence_reponse");
$non_reconnaissance_reponse=$pdo->getBareme("non_reconnaissance_reponse");

?>


 <section class="services" id="services"> 
    <h1 class="home-title">PARAMETRES</h1>
	<body>
	<div class="bloc_parametres">
        <form method="post" action="../model/enregistreParam.php" style="text-align: center;" >
		<br><h2 class="title-text" style="text-decoration: underline; color: black;"> BAREME : </h2>
		 <br><label style="font-weight: bold;"> Veuillez saisir votre barème </label><br>
          
            <div class="bloc_bareme">		
		
                <label class="lib_imput" > Bonne réponse : </label> 
			
                <input type ="number" step="any" name ="bonne_reponse" value=<?php echo $bonne_reponse ?> required>
                <label class="lib_imput"> Mauvaise réponse : </label> 
                <input type ="number" step="any" name ="mauvaise_reponse" value=<?php echo $mauvaise_reponse ?> required>
                <label class="lib_imput"> Absence de réponse : </label> 
                <input type ="number" step="any" name ="absence_reponse" value=<?php echo $absence_reponse ?> required>
                <label class="lib_imput"> Non reconnaissance de réponse : </label> 
                <input type ="number" step="any" name ="non_reconnaissance_reponse" value=<?php echo $non_reconnaissance_reponse ?>  required><br>
            </div>
	</div>
		<br>
		<br>
		<br><center><input class="bouton_valider" type="submit" value="Valider" ></center>
                      
        </form>
    </body>     
</section>
<?php
include("footer.html") ;
?>
