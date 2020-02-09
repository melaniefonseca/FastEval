<?php

/**
 * Description of PdoFastEval
 *
 * @author FONSECA NASCIMENTO Mélanie
 */
class PdoFastEval {
    private static $serveur='mysql:host=localhost';
    private static $bdd='dbname=fasteval;charset=utf8'; 
    private static $user='root' ;    		
    private static $mdp='' ;
    private static $monPdo;
    private static $monPdoFastEval=null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */				
    private function __construct(){
        PdoFastEval::$monPdo = new PDO(PdoFastEval::$serveur.';'.PdoFastEval::$bdd, PdoFastEval::$user, PdoFastEval::$mdp); 
        PdoFastEval::$monPdo->query("SET CHARACTER SET utf8");
    }
    public function _destruct(){
        PdoFastEval::$monPdo = null;
    }
    public  static function getPdoFastEval(){
        if(PdoFastEval::$monPdoFastEval==null){
            PdoFastEval::$monPdoFastEval= new PdoFastEval();
        }
        return PdoFastEval::$monPdoFastEval;  
    }
    public function getBareme($leLibelle){
        $res=PdoFastEval::$monPdo->query("select VALUE from parametres where NAME='$leLibelle'");
        $laLigne=$res->fetch();
        $valeur = $laLigne['VALUE'];
        return $valeur;
    }
    public function majParametre($leLibelle, $laValeur){
        $req = "update PARAMETRES set PARAMETRES.VALUE = '$laValeur'
        where PARAMETRES.NAME = '$leLibelle'";
        PdoFastEval::$monPdo->exec($req);	
    }
}