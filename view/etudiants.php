<?php
include("header.html") ;
require_once ("../include/PdoFastEval.php");
$pdo = PdoFastEval::getPdoFastEval();

?>
 <section class="services" id="services"> 
     <h1 class="home-title">Etudiants</h1><br>
    <body>
        <div class="bloc_correction">
            <form action="../model/formEtudiant.php" method="post">
            <br><h2 class="title-text" style="text-decoration: underline; color: black;"> Etudiants Anonymes/Nominatifs : </h2>
            <select name='Anonyme'>
                <option value="-1">Sélectionnez un type d'étudiants</option>
                <option value="1">Anonyme</option>
                <option value="0">Nominatif</option>
            </select><br>
            <input class="bouton_valider" type="submit" name="form" value="Crée les étudiants via un formulaire" ><br><br>
            <input class="bouton_valider" type="submit" name="xls" value="Crée les étudiants via un ficher xls" ><br><br>
            </form>
        </div>
    </body>
 </section>
<?php
include("footer.html") ;
?>
