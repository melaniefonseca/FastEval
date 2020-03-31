<?php
include("header.html") ;
require_once ("../include/PdoFastEval.php");


$idevaluation = isset($_GET['ideval']) ? $_GET['ideval']:false;

$pdo = PdoFastEval::getPdoFastEval();
$ideval=$pdo->getID();
?>

 <section class="services" id="services"> 
    <h1 class="home-title">STATISTIQUES</h1>
    <br>
	

<div class="bloc_statistiques">
<br>
<h2 class="title-text" style="text-decoration: underline; color: black;"> CHOIX DE L'EVALUATION : </h2> <br>
<center><label style="font-weight: bold;"> Sélectionner un sujet déjà corrigé : </label></center><br>
<div class="liste_statistiques">
<form action='/FastEval/View/statistiques' method='get'>
<select name='ideval' onchange='submit();return false;'>

 <?php 
if ( $idevaluation == '') { ?>
  <option value='0'>--- Choisir ---</option> 
  <?php
}
else { ?>
  <option value = '<?php print_r($idevaluation) ?>' > Evaluation  <?php print_r($idevaluation)  ?> </option> 
  <?php
}

 ?>
  
<?php
  for ($i = 0 ; $i < sizeof($ideval) ; $i++) { 
    if ($ideval[$i][0]!=$idevaluation){
      ?>
        <option value = '<?php print_r($ideval[$i][0]) ?>' > Evaluation  <?php print_r($ideval[$i][0])  ?> </option>
      <?php
    }
  }
?>




<br>
</select>
</form>
</div>
<br>
</div>
<br>



<?php

if($idevaluation)
{

$pdo = PdoFastEval::getPdoFastEval();
$nom_promo =$pdo-> getPromo($idevaluation);
$date_eval=$pdo-> getDate($idevaluation);
$type_eval=$pdo-> getType($idevaluation);
$nombres_notes=$pdo-> getNombres($idevaluation);
$moyenne=$pdo-> getMoyenne($idevaluation);
$mediane=$pdo-> getMediane($idevaluation);
$note_haute=$pdo-> getNoteHaute($idevaluation);
$note_basse=$pdo-> getNoteBasse($idevaluation);
$nbnotesinf10 = $pdo->getNombresdeNoteInferieureA($idevaluation,10);
$nbnotessupouegal10 = $pdo->getNombresdeNoteSuperieurouegaleA($idevaluation,10);
$note04 = $pdo-> getNombresdeNoteEntre($idevaluation,0,4);
$note58 = $pdo->getNombresdeNoteEntre($idevaluation,5,8);
$note912 = $pdo->getNombresdeNoteEntre($idevaluation,9,12);
$note1316 = $pdo->getNombresdeNoteEntre($idevaluation,13,16);
$note1720 = $pdo->getNombresdeNoteEntre($idevaluation,17,20);

?>



<script src="https://www.gstatic.com/charts/loader.js"></script>
 <script>
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Note');
      data.addColumn('number', 'Nb de notes');
      data.addRows([
        ['Note <10', <?php echo $nbnotesinf10; ?>],
        ['Note >=10', <?php echo $nbnotessupouegal10; ?>]
        ]);

      // Instantiate and draw the chart.
      var chart = new google.visualization.PieChart(document.getElementById('myPieChart'));

      var options = {
           title:"Proportions des notes par rapports à 10",
           titleTextStyle: {
      bold: true,
      italic: true,
      fontSize: 12,
  },
          colors: ['mediumturquoise', 'teal'],
          pieSliceText:'none',
          legend: {
            alignment: 'center',
            position: 'bottom'
          }
        };

      chart.draw(data, options);






var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        ["0-4", <?php echo $note04; ?>, "mediumturquoise"],
        ["5-8",<?php echo $note58; ?>, "mediumturquoise"],
        ["9-12",<?php echo $note912; ?>, "mediumturquoise"],
        ["13-16", <?php echo $note1316; ?>, "mediumturquoise"],
        ["17-20", <?php echo $note1720; ?>, "mediumturquoise"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title:"Répartions des notes",        
         titleTextStyle: {
         bold: true,
        italic: true,
        fontSize: 12,
      },
        width: 400,
        height: 400,
        bar: {groupWidth: "65%"},
        legend: { position: "none" },
      };


 var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));

chart.draw(view, options);




    }
  </script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


		<div class="bloc_statistiques">
			 <br><h2 class="title-text" style="text-decoration: underline; color: black;"> INFORMATION : </h2> 
      
			<br><label style="font-weight: bold;"> Promotion : <?php echo $nom_promo ?></label><br>
			<br><label style="font-weight: bold;"> Date de l'évaluation : <?php echo $date_eval ?> </label><br>
			<br><label style="font-weight: bold;"> Type d'examen : <?php echo $type_eval ?> </label><br>
    
		</div>
		<br>
		
		<div class="bloc_statistiques">
			<br><h2 class="title-text" style="text-decoration: underline; color: black;"> GENERALITE : </h2>
			<br><label style="font-weight: bold;"> Nombres de notes :  <?php print_r($nombres_notes) ?> </label><br>
		      <br><label style="font-weight: bold;"> Moyenne : <?php print_r($moyenne) ?> </label><br>
      <br><label style="font-weight: bold;"> Médianne : <?php print_r($mediane) ?> </label><br>
    </div> <BR> 
      
			<div class="bloc_statistiques">
<br><h2 class="title-text" style="text-decoration: underline; color: black;"> DETAIL DES NOTES: </h2> <br>

        <br><label style="font-weight: bold;"> Nombres de notes inferieur à 10 : <?php print_r($nbnotesinf10) ?> </label><br> 

     
      <br><label style="font-weight: bold;"> Nombres de notes supérieur ou égal à 10 : <?php print_r($nbnotessupouegal10) ?> </label><br>
       <div class="piechart">
    <div id="myPieChart" style='height:200px;'></div>
    </div>

			<br><label style="font-weight: bold;"> Note la plus haute :  <?php print_r($note_haute)  ?> </label><br>
			<br><label style="font-weight: bold;"> Note la plus basse :  <?php print_r($note_basse) ?> </label> <br> 

		
<div class="columnchart d-flex justify-content-center">

<div id="columnchart_values" style="width: 1000px; height: 350px;"></div>
</div>
<br>

<br>
<br>

</div>




<?php

}

?>

	
</section>

<?php


include("footer.html") ;
?>