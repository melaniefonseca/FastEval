<?php
include("header.html") ;
require_once ("../include/PdoFastEval.php");

$pdo = PdoFastEval::getPdoFastEval();
$libelle=$pdo->getLibelle();
$id=$pdo->getIdSujet();
$n=$pdo->getNombresIdSujet(); 
$idpromotion=$pdo->getIdpromotion();
$evaluations=$pdo->getIdevaluation();
$sujet=$pdo->getIdsujet();

?>
<section class="services" id="services"> 
    <h1 class="home-title">SUJETS</h1>
    <br>
    <div class="bloc_correction">
        <br><h2 class="title-text" style="text-decoration: underline; color: black;"> AJOUTER UNE EVALUATION : </h2>
        <div class="centre_blocTexte" style="width: 50%;text-align: center;margin: 0 auto;">
            <form method="POST" action="../model/AjouterEvaluation.php"><br>
                <P><br>
                    <label>Date Evaluation * : </label> 
                    <input type="date" name="dateEvaluation" id='dateEvaluation' style="background-image:url(../content/img/calendrier.png);background-position:right;background-repeat:no-repeat;background-size:16px;"> 
                    <br>
                </P>
                <P>
                    <select name='CC_EX' >
                        <option value="-1">Sélectionnez un type d'examen</option>
                        <option value="CC">Controle continu</option>
                        <option value="EX">Examen</option>
                    </select><br><br>
                </P>
                <select name='idpromotion'>
                    <option value="-1">Sélectionner la promotion</option>
                    <?php

                    for ($i = 0 ; $i < sizeof($idpromotion) ; $i++) { 
                        $libellepromotion=$pdo->getLibellePromotionById($idpromotion[$i][0]);
                        ?>
                        <option value = '<?php print_r($idpromotion[$i][0]) ?>'  label='<?php echo $libellepromotion ?>'> </option>
                        <?php
                    }
                    ?>
                </select><br><br><br>
                <label>Nombre d'étudiants * : </label> 
                <input type="number" name="nbEtudiants" id='nbEtudiants'><br>
                
                <input class="bouton_valider" type="submit" value="Valider" ><br><br>
            </form>
        </div>
    </div>
    <br><br>
    <div class="bloc_correction"><br>
        <h2 class="title-text" style="text-decoration: underline; color: black;"> AJOUTER UN SUJET : </h2><br>
        <div class="centre_blocTexte">
            <form method="POST" action="../model/EnregistreSujet.php" enctype="multipart/form-data"><br>
                <label > Libelle : </label> 
                <input type ="text" name ="libelle_sujet" value="" ><br>
                <select name='idevaluation' style="width: 50%;text-align: center;margin: 0 auto;">
                    <option value="-1">Sélectionner l'évaluation</option>
                    <?php

                    for ($i = 0 ; $i < sizeof($evaluations) ; $i++) { 
                        $libelleeevaluation=$pdo->getLibelleEvaluationById($evaluations[$i][0]);
                        ?>
                        <option value = '<?php print_r($evaluations[$i][0]) ?>'  label='<?php echo $libelleeevaluation ?>'> </option>
                        <?php
                    }
                    ?>
                </select><br><br><br>
                <label > Fichier : </label> <br>
                <input type="file" name="sujet" id="sujet" accept=".txt" value=""><br><br>
                <input class="bouton_valider" type="submit" id="Valider" name="Valider" value="Valider" ><br><br>
            </form>
        </div>
    </div>
    <br><br>
    <div class="bloc_correction"><br>
        <h2 class="title-text" style="text-decoration: underline; color: black;"> GENERER UN SUJET :  </h2><br>

        <form method="post" action="../model/GenererPDF.php" style="text-align: center;">
            <div class="bloc_correction">
                <select name='idsujet'>
                    <option value="-1">Choisissez votre sujet</option>
                    <?php
                    for ($i = 0 ; $i < sizeof($id) ; $i++) { 
                        $libellesujet=$pdo->getLibelleById($id[$i][0]) . " (" . $pdo->getDate($pdo->getIdEvaluationByIdSujet($sujet[$i][0])) .")";
                        ?>
                        <option value = '<?php print_r($id[$i][0]) ?>|<?php print_r($sujet[$i][0]) ?>'  label='<?php echo $libellesujet ?>'> </option>
                        <?php
                    }
                   ?>
                </select>
            </div>
            <br>

            <br><input class="bouton_valider" type="submit" value="Générer" >
        </form>
        <br>
    </div>	
</section>

<?php
include("footer.html") ;
exit();
?>