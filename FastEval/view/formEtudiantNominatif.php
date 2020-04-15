<?php
include("header.html") ;
require_once ("../include/PdoFastEval.php");
$pdo = PdoFastEval::getPdoFastEval();
$identificationPromotion= isset($_GET['idpromo']) ? $_GET['idpromo']:false;
$pdo = PdoFastEval::getPdoFastEval();
$id=$pdo->getIdPromotion()

?>
 <section class="services" id="services"> 
    <h1 class="home-title">ETUDIANT NOMINATIF </h1>
    <body>
    	<form method="post" action="../model/EnregistreEtudiantN.php" style="text-align: center;">
    		<div class ="bloc_correction"><br>
    
    		<h2 class="title-text" style="text-decoration: underline; color: black;"> PROMOTION : </h2>
    		<br><label style="font-weight: bold;"> Sélectionner une promotion : </label><br>
    	   <select name='idpromo'>
                    <option value="-1">Choisissez la promotion </option>
                    <?php
                    for ($i = 0 ; $i < sizeof($id) ; $i++) { 
                    $libellePromotion=$pdo->getLibellePromotionById($id[$i][0]);
                        ?>
                        $idpromo=$id[$i][0];
                        <option value = '<?php print_r($id[$i][0]) ?>' label='<?php echo $libellePromotion ?>'> </option>
                        <?php
                    }
                   ?>
             </select>
    
     </div><br>
     <div class ="bloc_correction"><br>
         <div class ="bloc_bareme">
			<h2 class="title-text" style="text-decoration: underline; color: black;"> INFORMATION : </h2>
			<br><center><label style="font-weight: bold;"> Veuillez renseigner les champs ci-dessous : </label></center><br>
             <label class="lib_imput" > Numéro Etudiant : </label> 
                    	 <input type ="number" name ="numero_etudiant" value="" >
                    	 <label class="lib_imput"> Nom :</label> 
                    	 <input type ="text" name ="nom" value="" >
                    	 <label class="lib_imput"> Prénom :</label> 
                    	 <input type ="text" name ="prenom" value="" ><br>
		</div>
	</div>
		  <br><input class="bouton_valider" type="submit" value="Valider" name="Valider" >
	</form>
    </body>
 </section>
<?php
include("footer.html") ;
?>