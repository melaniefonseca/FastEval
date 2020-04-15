
<?php
require_once ("include/PdoFastEval.php");

session_start();
$pdo = PdoFastEval::getPdoFastEval();
include("View/home.html");

?>