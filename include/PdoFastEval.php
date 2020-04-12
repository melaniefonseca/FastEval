<?php

/**
 * Description of PdoFastEval
 *
 * @author FONSECA NASCIMENTO Mélanie
 */
class PdoFastEval {
    private static $serveur='mysql:host=localhost';
    private static $bdd='dbname=fasteval;charset=utf8'; 
    private static $user='root';    		
    private static $mdp='' ;
    private static $monPdo;
    private static $monPdoFastEval=null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */				
	 
    private function __construct(){
		try {
        PdoFastEval::$monPdo = new PDO(PdoFastEval::$serveur.';'.PdoFastEval::$bdd, PdoFastEval::$user, PdoFastEval::$mdp); 
		}
		 catch(exception $e) {
		die('Erreur '.$e->getMessage());
		}
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
		$sql="select p.VALUE from fasteval.parametres p where NAME='$leLibelle'";
        $res=PdoFastEval::$monPdo->query($sql);
		$laLigne=$res->fetch();
        $valeur = $laLigne['VALUE'];
        return $valeur;
    }

    public function majParametre($leLibelle, $laValeur){
        $req = "update PARAMETRES set PARAMETRES.VALUE = '$laValeur'
        where PARAMETRES.NAME = '$leLibelle'";
        PdoFastEval::$monPdo->exec($req);	
    }

    public function getNomSujet($evaluation){
        $sql="select distinct sujet.libelle from fasteval.sujet, evaluation, note where evaluation.id_sujet = sujet.id_sujet and note.id_evaluation = evaluation.id_evaluation and '$evaluation' = note.id_evaluation ";
        $res=PdoFastEval::$monPdo->query($sql);  
        $laLigne=$res->fetch();
        $valeur = $laLigne['libelle'];
        return $valeur;      
       
    }

    public function getDate($evaluation){
        $sql="select distinct date_evaluation as a from fasteval.evaluation, note n where evaluation.id_evaluation = n.id_evaluation  and '$evaluation' = n.id_evaluation ";
        $res=PdoFastEval::$monPdo->query($sql);    
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;
    }


      public function getType($evaluation){
        $sql="select n.type AS type from evaluation as n where n.id_evaluation = '$evaluation' ";
        $res=PdoFastEval::$monPdo->query($sql);    
        $laLigne=$res->fetch();
        $valeur = $laLigne['type'];    
        return $valeur;
    }

    public function getPromo($evaluation){
        $sql="select nom_promotion from promotion, note where promotion.id_promotion=note.id_promotion  and '$evaluation' = note.id_evaluation ";
        $res=PdoFastEval::$monPdo->query($sql);    
        $laLigne=$res->fetch();
        $valeur = $laLigne['nom_promotion'];    
        return $valeur;
    }

     public function getNombresdeNoteInferieureA($evaluation,$note){
        $sql="select count(p.note) AS nb from fasteval.note p where '$evaluation' = p.id_evaluation AND note < '$note' ";
        $res=PdoFastEval::$monPdo->query($sql);        
         $laLigne=$res->fetch();
        $valeur = $laLigne['nb'];    
        return $valeur;
    }

    public function getNombresdeNoteSuperieurouegaleA($evaluation,$note){
        $sql="select count(p.note) AS nb from fasteval.note p where '$evaluation' = p.id_evaluation AND note >= '$note' ";
        $res=PdoFastEval::$monPdo->query($sql);        
         $laLigne=$res->fetch();
        $valeur = $laLigne['nb'];    
        return $valeur;
    }


    public function getNombresdeNoteEntre($evaluation,$notebasse,$notehaute){
        $sql="select count(p.note) AS nb from fasteval.note p where '$evaluation' = p.id_evaluation AND p.note >= '$notebasse' ".($notehaute==20 ? "AND p.note <= '$notehaute' ":"AND p.note <= '$notehaute' ");
        $res=PdoFastEval::$monPdo->query($sql);        
        $laLigne=$res->fetch();
        $valeur = $laLigne['nb'] ;    
        return $valeur;
    }


    public function getNombres($evaluation){
        $sql="select count(p.note) AS nb from fasteval.note p where '$evaluation' = p.id_evaluation";
        $res=PdoFastEval::$monPdo->query($sql);        
        $laLigne=$res->fetch();
        $valeur = $laLigne['nb'];    
        return $valeur;
    }

    public function getMoyenne($evaluation){
        $sql="select avg(p.note) AS a from fasteval.note p where '$evaluation' = p.id_evaluation";
        $res=PdoFastEval::$monPdo->query($sql);  
        $laLigne=$res->fetch();
        $valeur = round($laLigne['a'],2);    
        return $valeur;
    }


     public function getMediane($evaluation){
        $sql="select p.note from fasteval.note p where '$evaluation' = p.id_evaluation order by p.note ASC";
        $res=PdoFastEval::$monPdo->query($sql);  
        $notes=$res->fetchAll();

        $nbnotes = count($notes);

        if($nbnotes % 2 ==0) {
             $indexmediane1 = floor($nbnotes/2)-1;
             $indexmediane2 = $indexmediane1+1;;
            $mediane = ($notes[$indexmediane2]['note'] + $notes[$indexmediane1]['note'])/2;
         }               
        else{
             $indexmediane = floor($nbnotes/2); 
             $mediane = $notes[$indexmediane]['note'];
        }
        
         return $mediane;
     }


    public function getNoteHaute($evaluation){
        $sql="select max(p.note) AS a from fasteval.note p where '$evaluation' = p.id_evaluation";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }

    public function getNoteBasse($evaluation){
        $sql="select min(p.note) as a from fasteval.note p where '$evaluation' = p.id_evaluation";
        $res=PdoFastEval::$monPdo->query($sql);        
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;
    }

    public function getiD(){
        $sql="select distinct id_evaluation from fasteval.note";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetchAll();
        return $laLigne;
    }

      public function getIdEtudiantByNumeroAnonymat($anonymat){
        $sql="select id_etudiant AS a from etudiant_anonyme where '$anonymat' = num_anonymat";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }

     public function getIdEtudiantByNumeroEtudiant($etudiant){
        $sql="select id_etudiant AS a from etudiant_nominatif where '$etudiant' = num_etudiant";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }

        public function getIdPromotionByNumeroEtudiant($etudiant){
        $sql="select id_promotion AS a from etudiant_nominatif where '$etudiant' = num_etudiant" ;
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }

         public function getIdPromotionByNumeroAnonymat($anonymat){
        $sql="select id_promotion AS a from etudiant_anonyme where '$anonymat' = num_anonymat" ;
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }

          public function getIdEvaluationByNumeroEtudiant($etudiant){
        $sql="select id_evaluation AS a from note,etudiant_nominatif where '$etudiant' = etudiant_nominatif.num_etudiant and note.id_etudiant=etudiant_nominatif.id_etudiant";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }

         public function getIdEvaluationByNumeroAnonymat($anonymat){
        $sql="select id_evaluation AS a from note,etudiant_anonyme where '$anonymat' = etudiant_anonyme.num_anonymat and note.id_etudiant=etudiant_anonyme.id_etudiant";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }

         public function insertEtudiantByNumero($idEvaluation, $etudiant, $idPromotion, $note){
        $req ="insert into note (id_evaluation, id_etudiant, id_promotion, note) 
        values ('$idEvaluation', '$etudiant', '$idPromotion', '$note')";
        PdoFastEval::$monPdo->exec($req);   
    }


 public function getLibelle(){
        $sql="select sujet.libelle from fasteval.sujet ";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetchAll();
        return $laLigne;
    }
   public function getLibelleById($id){
        $sql="select sujet.libelle as a from fasteval.sujet where '$id'=id_sujet ";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;  
    }

       public function getIdSujet(){
        $sql="select distinct sujet.id_sujet from fasteval.sujet";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetchAll();
        return $laLigne;
    }

    public function getNombresIdSujet(){
        $sql="select count(id_sujet) AS nb from sujet";
        $res=PdoFastEval::$monPdo->query($sql);        
        $laLigne=$res->fetch();
        $valeur = $laLigne['nb'];    
        return $valeur;
    }


      public function insertSujet($id_sujet,$libelle,$chemin){
        $req ="insert into sujet(id_sujet,libelle, chemin) 
        values ('$id_sujet', '$libelle', '$chemin')";
        PdoFastEval::$monPdo->exec($req);   
    }

    public function getMaxSujet(){
        $sql="select max(sujet.id_sujet) AS a from fasteval.sujet";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    
}