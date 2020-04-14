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

    public function getMaxSujet(){
        $sql="select max(sujet.id_sujet) AS a from fasteval.sujet";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    public function insertNote($id_evaluation, $id_etudiant, $id_promotion, $note){
        $req="INSERT INTO note (ID_EVALUATION, ID_ETUDIANT, ID_PROMOTION, NOTE) VALUES ('$id_evaluation','$id_etudiant','$id_promotion','$note')";
        PdoFastEval::$monPdo->exec($req); 
    }
    public function getIdPromotionByEtudiant($id_etudiant){
        $sql="select id_promotion AS a from etudiant where id_etudiant='$id_etudiant'" ;
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    public function getIdEtudiantByNumeroAnonymat($anonymat){
        $sql="select id_etudiant AS a from etudiant_anonyme where num_anonymat='$anonymat'";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }

     public function getIdEtudiantByNumeroEtudiant($etudiant){
        $sql="select id_etudiant AS a from etudiant_nominatif where num_etudiant='$etudiant'";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    public function insertEtudiantAnonyme($id_promotion,$numAnonymat){
        $sql="Select `insert_etuAnonyme` ('$id_promotion','$numAnonymat')" ;
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
    }
    public function insertEtudiantNominatif($id_promotion,$numEtudiant, $nom, $prenom){
        $sql="Select `insert_etuNominatif` ('$id_promotion','$numEtudiant', '$nom', '$prenom')";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
    }
    public function getIdPromotion(){
        $sql="select distinct promotion.id_promotion from fasteval.promotion";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetchAll();
        return $laLigne;
    }
    public function getLibellePromotionById($idPromotion){
        $sql="select promotion.nom_promotion as a from fasteval.promotion where id_promotion='$idPromotion' ";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;  
    }
    public function insertEvaluation($type, $nb_etudiant, $date_evaluation,$id_bareme){
        $req="INSERT INTO evaluation( TYPE, NB_ETUDIANT, DATE_EVALUATION, ID_BAREME) VALUES ('$type','$nb_etudiant', '$date_evaluation','$id_bareme')";
        PdoFastEval::$monPdo->exec($req); 
    }
    public function updateNbEtudiantEvaluation($idEvaluation, $nbEtudiant){
        $req="UPDATE evaluation set NB_ETUDIANT='$nbEtudiant' where id_evaluation='$idEvaluation')";
        PdoFastEval::$monPdo->exec($req); 
    }
    public function getMaxEvaluation(){
        $sql="select max(evaluation.id_evaluation) AS a from fasteval.evaluation";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    public function insertBareme($bonne_rep, $mauvaise_rep, $absence_rep, $non_reconnaissance){
        $req="INSERT INTO bareme( BONNE_REP, MAUVAISE_REP, ABSENCE_REP, NON_RECONNAISSANCE_REP) VALUES ('$bonne_rep', '$mauvaise_rep', '$absence_rep', '$non_reconnaissance')";
        PdoFastEval::$monPdo->exec($req); 
    }
    public function getMaxBareme(){
        $sql="select max(bareme.id_bareme) AS a from fasteval.bareme";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
          public function getNumeroEtudiantByIdEvaluation($evaluation){
        $sql="select etudiant_nominatif.num_etudiant from etudiant_nominatif, note where '$evaluation' = note.id_evaluation and note.id_etudiant=etudiant_nominatif.id_etudiant";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetchAll();
        return $laLigne;
    }
   public function getNomEtudiantByIdEvaluation($evaluation){
        $sql="select distinct etudiant_nominatif.nom_etudiant from etudiant_nominatif, note where '$evaluation' = note.id_evaluation and note.id_etudiant=etudiant_nominatif.id_etudiant";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetchAll();
        return $laLigne;
    }
  public function getPrenomEtudiantByIdEvaluation($evaluation){
        $sql="select distinct etudiant_nominatif.prenom_etudiant from etudiant_nominatif, note where '$evaluation' = note.id_evaluation and note.id_etudiant=etudiant_nominatif.id_etudiant";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetchAll();
        return $laLigne;
    }

    public function getNoteEtudiantByIdEvaluation($evaluation){
        $sql="select  note.note from note where '$evaluation' = note.id_evaluation";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetchAll();
        return $laLigne;
    }

    public function getNumeroAnonymatByIdEvaluation($evaluation){
        $sql="select distinct etudiant_anonyme.num_anonymat from etudiant_anonyme, note where '$evaluation' = note.id_evaluation and note.id_etudiant=etudiant_anonyme.id_etudiant";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetchAll();
        return $laLigne;
    }


     public function insertPromotion($nom_promotion, $date_promotion){
        $req="INSERT INTO promotion (nom_promotion, date_promotion) VALUES ('$nom_promotion', '$date_promotion')";
        PdoFastEval::$monPdo->exec($req); 
    }

     public function getMaxPromotion(){
        $sql="select max(promotion.id_promotion) AS a from fasteval.promotion";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    public function getIdEvalByIdSujet($idsujet){
        $sql="select id_evaluation AS a from fasteval.sujet where id_sujet='$idsujet'";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    public function getBaremeBonneRepByIDEvaluation($idevaluation){
        $sql="select BONNE_REP AS a from EVALUATION, BAREME where id_evaluation='$idevaluation' and evaluation.id_bareme=bareme.id_bareme";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    public function getBaremeMauvaiseRepByIDEvaluation($idevaluation){
        $sql="select MAUVAISE_REP AS a from EVALUATION, BAREME where id_evaluation='$idevaluation' and evaluation.id_bareme=bareme.id_bareme";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    public function getBaremeAbsencesRepByIDEvaluation($idevaluation){
        $sql="select ABSENCE_REP AS a from EVALUATION, BAREME where id_evaluation='$idevaluation' and evaluation.id_bareme=bareme.id_bareme";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    public function getBaremeNonRecoRepByIDEvaluation($idevaluation){
        $sql="select NON_RECONNAISSANCE_REP AS a from EVALUATION, BAREME where id_evaluation='$idevaluation' and evaluation.id_bareme=bareme.id_bareme";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    public function getTypeEvalByIDEval($idevaluation){
        $sql="select TYPE AS a from EVALUATION where id_evaluation='$idevaluation'";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
    public function getIdEvaluation(){
        $sql="select distinct evaluation.id_evaluation from fasteval.evaluation";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetchAll();
        return $laLigne;
    }
    
    public function ajouterCorrection($correction, $idSujet) {
        $req = "update SUJET set SUJET.CORRECTION = '$correction'
        where SUJET.ID_SUJET = '$idSujet'";
        PdoFastEval::$monPdo->exec($req);	
    }
    
    public function ajouterNumSujet($numSujet, $idSujet) {
        $req = "update SUJET set SUJET.NUM_SUJET = '$numSujet'
        where SUJET.ID_SUJET = '$idSujet'";
        PdoFastEval::$monPdo->exec($req);	
    }

    public function insertSujet($libelle,$chemin, $idEvaluation){
        $req ="insert into sujet(libelle, chemin, id_evaluation) 
        values ('$libelle', '$chemin', '$idEvaluation')";
        PdoFastEval::$monPdo->exec($req);   
    }

    public function getNumSujet($idSujet){
        $sql="select sujet.num_sujet AS a from fasteval.sujet where '$idSujet'=id_sujet ";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }

    public function getMaxNumSujet(){
        $sql="select max(sujet.num_sujet) AS a from fasteval.sujet";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;   
    }
        public function getLibelleEvaluationById($idEvaluation){
        $sql="select evaluation.date_evaluation as a, evaluation.type as b from fasteval.evaluation where id_evaluation='$idEvaluation' ";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = date('d-m-Y', strtotime($laLigne['a']))." (".$laLigne['b'].")";    
        return $valeur;  
    }
    public function getIdEvaluationByIdSujet($idSujet){
        $sql="select sujet.id_evaluation as a from fasteval.sujet where '$idSujet' = sujet.id_sujet ";
        $res=PdoFastEval::$monPdo->query($sql);    
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;
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

    public function getNombresSujetByIdEval($idEvaluation, $idSujet){
        $sql="select count(id_sujet) AS nb from sujet where '$idEvaluation'=id_evaluation and correction!='' and '$idSujet'!=id_sujet ";
        $res=PdoFastEval::$monPdo->query($sql);        
        $laLigne=$res->fetch();
        $valeur = $laLigne['nb'];    
        return $valeur;
    }

    public function getCheminSujet($idSujet){
        $sql="select sujet.chemin AS a from fasteval.sujet where '$idSujet'=id_sujet";
        $res=PdoFastEval::$monPdo->query($sql);        
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;
    }
    
    public function getAnonymat($idEvaluation) {
        $anonymat = true;
        
        $sql="select evaluation.type as a from fasteval.evaluation where '$idEvaluation'=id_evaluation ";
        $res=PdoFastEval::$monPdo->query($sql);     
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];  
        
        if ($valeur == "CC") {
            $anonymat = false;
        }
        return $anonymat;  
    }
    public function getCorrectionByIdSujet($idSujet){
        $sql="select sujet.correction AS a from fasteval.sujet where '$idSujet'=id_sujet";
        $res=PdoFastEval::$monPdo->query($sql);        
        $laLigne=$res->fetch();
        $valeur = $laLigne['a'];    
        return $valeur;
    }

}