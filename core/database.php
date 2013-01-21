<?php

/**
 * LetDoo PHP : Rapid Development Framework
 * Design patterns  : MVC
 * 
 * @author : MARTIN Franck
 * @date : 2012
 * 
 * @version : 1.0 beta
 */


/**
 * Class Database
 * Etablit et gère les connexions à la base de donnée
 * Et fournit des méthodes pour effectuer des requètes SQL plus simplement
 */
class Database
{
	/**
	 *Base infos
	 */
	private $server="localhost";
	private $dbname="db";
	private $login="root";
	private $password="";
	
	
	/**
	 * Dernière requete SQL effectuée
	 */
	protected $lastSql;
	
	
	/**
	 * Nombres de résulats pour la dernière requete SQL effectuée
	 */
	protected $count;
	
	/**
	 * Base utilisée dans la config
	 */
	protected $dbUsing="default";
	
	
	/**
	 * Contient chaque connexion pour chaque base utilisée
	 */
	static private $connexion=array();

	
	/**
	 * Etablit et gère les connexions à la base de donnée
	 * @param $baseInfos : information de connexion
	 */
	public function __construct ($baseInfos=null){
		$this->server=Config::$baseInfos[$this->dbUsing]["server"];
		$this->dbname=Config::$baseInfos[$this->dbUsing]["dbname"];
		$this->login=Config::$baseInfos[$this->dbUsing]["login"];
		$this->password=Config::$baseInfos[$this->dbUsing]["password"];

		if(!isset(self::$connexion[$this->dbUsing])){
			try{
				$DB = new PDO("mysql:host=$this->server;dbname=$this->dbname",$this->login,$this->password,
						array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8')
						);
				$DB->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				self::$connexion[$this->dbUsing]=$DB;
			}
			catch(PDOException $e){
				Core::defineError("Erreur connexion PDO >> ".$e->getMessage());
			}
		}
	}
	
	
	/**
	 * Exécute une requete SQL et retourne le résultat
	 * @param $sql : requete sql
	 * @param $type : type de retour de résultat
	 * @return type 
	 */
	public function query($sql,$type=null){
		try{
			$resulat = self::$connexion[$this->dbUsing]->query($sql);
			$this->lastSql=$sql;
			$this->count= $resulat->rowCount($sql);
			
			if($type!=null){
				$typeResult=$this->fetch($resulat,$type);
				return $typeResult;
			}
			else{
				return true;
			}
			
		}
		catch (PDOException $e){
			Core::defineError("Erreur requete >> ".$sql);
			return false;
		}
	}
		
	
	/**
	 * Retourne un résultat d'une requete selon le type
	 * @param $resulat : résultat d'une requete
	 * @param $type : type de retour
	 * @return  résultat 
	 */
	private function fetch($resulat,$type){
		switch ($type){ 
			case "OBJ": 
			return $resulat->fetch(PDO::FETCH_OBJ);
			break;
		
			case "ASSOC": 
			return $resulat->fetch(PDO::FETCH_ASSOC);
			break;
		
			case "BOTH": 
			return $resulat->fetch(PDO::FETCH_BOTH);
			break;
		
			case "ALL": 
			return $resulat->fetchAll(PDO::FETCH_OBJ);
			break;
		
			default:
			return $resulat;
		}
	}
	
	
	/**
	 * Créer une requete de type 'select'
	 * @param $table : nom de la table
	 * @param $params : parametre de requete
	 * @return type 
	 */
	public function select($table,$params=array()){
		if (isset($params['fields'])) {$c=$params['fields']; } else { $c="*";}
		if (isset($params['type'])) {$type=$params['type']; } else { $type="OBJ";}
		$sql="SELECT $c FROM $table ";
		
		if(isset($params['cond'])){
			if(!is_string($params['cond'])){
				$long=sizeof ($params['cond']);
				$i=0;

				 foreach($params['cond'] as $c=>$v) {
					 $i++;
					 if($i==1) { $sql.="WHERE "; } else { $sql.=" AND "; }
					 $n=strpos($v,".");
					 if(!is_numeric($v) && $n==0) {$v="'$v'";}
					 $sql.=$c."=".$v;
				 }
			}
			else{
				$sql.=" WHERE {$params['cond']} ";
			}
		}
		
		if (isset($params['order'])) { $sql.= " ORDER BY ". $params['order']; }
		if (isset($params['limit']))  { $sql.= " LIMIT ". $params['limit']; }
		
		return $this->query($sql,$type);
	}
	
	
	/**
	 * Créer une requete de type 'insert'
	 * @param $table : nom de la table
	 * @param $listval : données à insérer
	 * @return type 
	 */
	public function insert($table,$listval=array()){
		$long=sizeof ($listval);
		
		$sql="INSERT INTO $table ";
		$champs="(";
		$valeurs="(";
		
		foreach ($listval as $c=>$val){
			$champs.=$c.",";
			if($val=="date") { $valeurs.="NOW() ,"; } 
			else { $valeurs.="'$val',"; }
		}
		$champs=substr($champs, 0,-1);
		$valeurs=substr($valeurs, 0,-1);
		
		$champs.=")";
		$valeurs.=")";
		$sql.=$champs." VALUES ".$valeurs;
		
		return $this->query($sql);
	}
	
	
	/**
	 * Créer une requete de type 'update'
	 * @param $table : nom de la table
	 * @param $listval : données à update
	 * @param $cond : conditions
	 * @return type 
	 */
	public function update($table,$listval=array() ,$cond=null){
		$long=sizeof ($listval);
		$sql="UPDATE $table SET ";
		
		foreach ($listval as $c=>$val){
			if($val=="date") { $sql.=" $c=NOW() ,"; } 
			else {$sql.=" $c='$val' ,"; }
		}
		$sql=substr($sql, 0,-1);
		
		if($cond!=null){
			if(!is_string($cond)){
				$long=sizeof ($cond);

				$sql.="WHERE ";
				$i=0;

				foreach ($cond as $c=>$res){
					$i++;
					if($i==$long) { $sql.=" $c='$res' "; }
					else{ $sql.=" $c='$res' AND "; }
				}
			}else{
				$sql.=" WHERE {$cond} ";
			}
		}
		return $this->query($sql);
	}
		
	
	/**
	 * Retourne un tableau de la structure de la table (!! Seulement avec MySQL !!)
	 * @param  $table : nom de la table
	 * @return array() 
	 */
	public function getColumnTable($table){
		$res = $this->query("SHOW COLUMNS FROM $table","ALL");
		$d=array();
		foreach ($res as $k => $v) {
			$d[$k]=$v->Field;
		}
		return $d;
	}
	
	
	/**
	 * Retourne la dernière requete SQL effectuée
	 * @return string 
	 */
	public function getSql(){
		return $this->lastSql;
	}
	
	
	/**
	 * Retounr le nombres de résulats pour la dernière requete SQL effectuée
	 * @return int 
	 */
	public function getCount(){
		return $this->count;
	}
	
}
?>