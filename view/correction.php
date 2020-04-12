<?php
include("header.html") ;
require_once ("../include/PdoFastEval.php");
$identificationSujet= isset($_GET['idsujet']) ? $_GET['idsujet']:false;
$pdo = PdoFastEval::getPdoFastEval();
$id=$pdo->getIdSujet();


?>
 <section class="services" id="services"> 
    <h1 class="home-title">Correction</h1>
	<body>
        <form method="post" action="../model/CorrectionCopie.php" style="text-align: center;">
            <div class="bloc_correction">
		<br><h2 class="title-text" style="text-decoration: underline; color: black;"> CHOIX DU SUJET : </h2>
                <br><label style="font-weight: bold;"> Sélectionner un sujet déjà existant sur le site : </label><br>
                <select name='idsujet'>
                    <option value="-1">Choisissez votre sujet</option>
                    <?php
                    for ($i = 0 ; $i < sizeof($id) ; $i++) { 
                    $libellesujet=$pdo->getLibelleById($id[$i][0]);
                        ?>
                        $idsujet=$id[$i][0];
                        <option value = '<?php print_r($id[$i][0]) ?>'  label='<?php echo $libellesujet ?>'> </option>
                        <?php
                    }
                   ?>
                </select>
            </div>
            <br>
            <div class="bloc_correction">
                <br><h2 class="title-text" style="text-decoration: underline; color: black;"> IMPORTATION DES COPIES : </h2>
                <div class="centre_blocTexte">
                    <label style="font-weight: bold;"> Importer/Scanner la ou les copies : </label><br>
                    <input type="file" name="Import/scanne" accept=".pdf" value="Importer/Scanner" capture multiple=""><br><br>
                </div>
            </div>
            <br><input class="bouton_valider" type="submit" value="Valider" >
        </form>
    </body>    
</section>
<?php
include("footer.html") ;
?>
