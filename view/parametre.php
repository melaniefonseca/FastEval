<?php
include("header.html") ;
require_once ("../include/PdoFastEval.php");

$pdo = PdoFastEval::getPdoFastEval();
$bonne_reponse=$pdo->getBareme("bonne_reponse");
$mauvaise_reponse=$pdo->getBareme("mauvaise_reponse");
$absence_reponse=$pdo->getBareme("absence_reponse");
$non_reconnaissance_reponse=$pdo->getBareme("non_reconnaissance_reponse");
$sujet=$pdo->getBareme("stockage_sujet");
$copies=$pdo->getBareme("stockage_copies_corrige");

?>
<div class="wrapper">
    <div class="content home">
         <div class="content-overlay">
            <div class="container container-main">
                <div class="home-title">
                    <h1 id="home-title-welcome">Paramètres</h1>
                </div>
               
                <body>
                    <form method="post" action="../model/enregistreParam.php" style="text-align: center;" >
                        <br><br><h3 style="text-align: center;">Veuillez saisir votre barème </h3><br>
                    	<div class="bloc_bareme">			
                            <label > Bonne réponse : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label> 
                            <input type ="number" step="any" name ="bonne_reponse" value=<?php echo $bonne_reponse ?> required><br>
                            <label > Mauvaise réponse : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 
                            <input type ="number" step="any" name ="mauvaise_reponse" value=<?php echo $mauvaise_reponse ?> required><br>
                            <label > Absence de réponse : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 
                            <input type ="number" step="any" name ="absence_reponse" value=<?php echo $absence_reponse ?> required><br>
                            <label > Non reconnaissance de réponse : </label> 
                            <input type ="number" step="any" name ="non_reconnaissance_reponse" value=<?php echo $non_reconnaissance_reponse ?>  required><br>
                        </div>
                        <br><h3 style="text-align: center;">Veuillez indiquer vos chemins</h3><br>
                        <div class="bloc_bareme">
                            <label > Stockage des sujets : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 
                            <input type ="text" name ="stockage_sujet" value=<?php echo $sujet ?> required><br>
                            <label > Stockage des copies corrigées :&nbsp;&nbsp;&nbsp;</label> 
                            <input type ="text" name ="stockage_copies_corrige" value=<?php echo $copies ?> required>
                        </div>
                           
                        <br><br><input class="bouton_valider_correction" type="submit" value="Valider" >
                        
                    </form>
                 
                </body>     
            </div>
        </div>
    </div>
</div>
<?php
include("footer.html") ;
?>