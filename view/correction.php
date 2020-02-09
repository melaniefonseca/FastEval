<?php
include("header.html") ;
?>
<div class="wrapper">
    <div class="content home">
         <div class="content-overlay">
            <div class="container container-main">
                <div class="home-title">
                    <h1 id="home-title-welcome">Correction</h1><br>
                </div>
                <body>
                    <form method="post" action="" >
                        <div class="bloc_correction">
                            <br><h3 style="text-decoration: underline;"> CHOIX DU SUJET : </h3>
                            <br><label > Sélectionner un sujet déjà existant sur le site : </label> 
                            <input type ="text" name ="sujet_site" value="" readonly="">
                            <input class="bouton_f_correction" type="button" value="Sélectionner"><br>
                            <br><label class="centre" style="font-weight: bold;"> OU </label>
                            <br> <label > Importer un nouveau sujet : </label> <br>
                            <div class="centre_blocTexte">
                                <br> <label > Libelle : </label> 
                                <input type ="text" name ="libelle_sujet" value="" ><br>
                                <br> <label > Fichier : </label> 
                                <input type="file" name="nv_sujet" accept=".pdf" value="Sélectionner" multiple=""><br><br><br>
                            </div>
                        </div>
                        <br>
                        <div class="bloc_correction">
                            <br><h3 style="text-decoration: underline;"> IMPORTATION DES COPIES : </h3>
                            <div class="centre_blocTexte">
                                <br><label > Importer/Scanner la ou les copies : </label> 
                                <input type="file" name="Import/scanne" accept="image/*" value="Importer/Scanner" capture multiple=""><br><br>
                            </div>
                        </div>
                        <input class="bouton_valider_correction" type="submit" value="Valider" >
                    </form>
                </body>    
                        
            </div>
        </div>
    </div>
</div>
<?php
include("footer.html") ;
?>