<?php
include("header.html") ;
?>
 <section class="services" id="services"> 
    <h1 class="home-title">Correction</h1>
	<body>
        <form method="post" action="../model/CorrectionCopie.php" style="text-align: center;">
            <div class="bloc_correction">
		<br><h2 class="title-text" style="text-decoration: underline; color: black;"> CHOIX DU SUJET : </h2>
                <br><label style="font-weight: bold;"> Sélectionner un sujet déjà existant sur le site : </label><br>
                <input type ="text" class="input_text" style="margin-left: 7%;" name ="sujet_site" value="" readonly="">
		<input class="bouton_f_correction" type="button" value="Sélectionner"><br>
		<label class="centre" style="font-weight: bold;"> OU </label>
		<br> <label style="font-weight: bold;"> Importer un nouveau sujet : </label> 
		<div class="centre_blocTexte">
                    <br> <label > Libelle : </label> 
                    <input type ="text" name ="libelle_sujet" value="" >
                    <label > Fichier : </label> 
                    <input type="file" name="nv_sujet" accept=".pdf" value="Sélectionner"><br><br>
                </div>
            </div>
            <br>
            <div class="bloc_correction">
                <br><h2 class="title-text" style="text-decoration: underline; color: black;"> IMPORTATION DES COPIES : </h2>
                <div class="centre_blocTexte">
                    <label style="font-weight: bold;"> Importer/Scanner la ou les copies : </label><br>
                    <input type="file" name="Import/scanne" accept="image/*" value="Importer/Scanner" capture multiple=""><br><br>
                </div>
            </div>
            <br><input class="bouton_valider" type="submit" value="Valider" >
        </form>
    </body>    
</section>
<?php
include("footer.html") ;
?>
