<?php
include("header.html") ;
require_once ("../include/PdoFastEval.php");
$identificationPromotion= isset($_GET['idpromo']) ? $_GET['idpromo']:false;
$pdo = PdoFastEval::getPdoFastEval();
$id=$pdo->getIdPromotion();

?>
 <section class="services" id="services"> 
     <h1 class="home-title">Etudiants</h1><br>
    <body>
        <div class="bloc_correction">
            <form action="../model/formEtudiant.php" method="post">
            <br><h2 class="title-text" style="text-decoration: underline; color: black;"> ETUDIANT NOMINATIF/ANONYME  : </h2>
            <select name='Anonyme'>
                <option value="-1">Sélectionnez un type d'étudiants</option>
                <option value="1">Anonyme</option>
                <option value="0">Nominatif</option>
                 </select><br><br>
                 <input class="bouton_valider" type="submit" name="form" value="Créer les étudiants via un formulaire" ><br><br>
                </div>
                   <br><br> <div class="bloc_correction">
                 <br><h2 class="title-text" style="text-decoration: underline; color: black;"> FICHIER EXCEL  : </h2><br>
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

                             <label > Fichier : </label> <br>
                             <input type="file" name="fichierxls" id="fichierxls" accept=".xls" value="" multiple=""><br><br>

            <input class="bouton_valider" type="submit" name="xls" value="Créer les étudiants via un ficher xls" ><br><br>
        </div> 
    </form>
        <br><br>
          <form action="../model/EnregistrePromotion.php" method="post">
          <div class="bloc_correction">
            <br><h2 class="title-text" style="text-decoration: underline; color: black;"> CREATION DE PROMOTION : </h2> 
            <div class ="bloc_bareme">
            <br><center><label style="font-weight: bold;"> Veuillez renseigner les champs ci-dessous : </label></center><br>
                         <center><label class="lib_imput"> Nom :</label> </center>
                         <input type ="text" name ="nom_promotion" value="" > 
                       <center>   <label class="lib_imput" > Année : </label> </center>
                         <input type ="date" name ="date_promotion" value="" >
                         </div>
                       <br> <input class="bouton_valider" type="submit" value="Valider" name="Valider" ><br><br>
            
        
        </div>
</form>

    </body>
 </section>
<?php
include("footer.html") ;
?>
