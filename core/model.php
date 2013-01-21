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
 * Class Model
 * Fournit des méthodes rapide pour effectuer des requêtes SQL
 */
class Model extends Database{
	
	
	/**
	 * Tableau représentant la structure de la table
	 */
	protected $columns=array();
	
	
	/**
	 * Clé primaire de la table
	 */
	protected $primary_key="id";
	
	
	/**
	 * Nom de la table
	 */
	protected $table;
	
	
	/**
	 * Etablit la connexion via la class parent
	 */
	public function __construct($table=null,$dbUsing=null) {
		if(isset($dbUsing)){
			if(array_key_exists($dbUsing,Config::$baseInfos)){
				$this->dbUsing=$dbUsing;
			}else{
				Core::defineError("La base '$dbUsing' n'est pas définie dans la configuration.");
			}
		}
		parent::__construct();
		if(isset($table)){ $this->table=$table; }
	}
	
	
	/**
	 * Retourne les liste de tous les enregistrements
	 * @param $limit : nombre d'enregistrements voulus
	 * @return PDOStatement Object
	 */
	public function all($limit=null){
		$d=$this->select($this->table,array(
			"limit"=>$limit,
			"type"=>"null"
		));
		return $d;
	}
	
	
	/**
	 * Retourne les liste de tous les enregistrements
	 * @param $field : champs à trier
	 * @param $order : 0 = ASC ; 1 = DESC
	 * @param $limit : nombre d'enregistrements voulus
	 * @return PDOStatement Object
	 */
	public function all_OrderBy($field,$order=0,$limit=null){
		if($order==0){$order="ASC";}else{$order="DESC";}
		$d=$this->select($this->table,array(
			"limit"=>$limit,
			"order"=>$field." ".$order,
			"type"=>"null"
		));
		return $d;
	}
	
	
	/**
	 * Vérifie si un enregistrement existe
	 * @param $val : valeur du champs de la condition
	 * @param $field : champs de la condition
	 * @return bool 
	 */
	public function find($val,$field=null){
		if(!isset($field)){ $field=$this->primary_key; }
		$exist = $this->select($this->table,array(
			"fields"=>"COUNT(*) AS exist",
			"cond"=>"$field='$val'"
			)
		);
		if($exist->exist>0){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Retourne un enregistrement
	 * @param $fieldVal : champs à récupérer
	 * @param $val : valeur du champs de la condition
	 * @param $field : champs de la condition
	 * @return Object
	 */
	public function first($fieldVal,$val,$field=null){
		if(!isset($field)){ $field=$this->primary_key; }
		$find = $this->select($this->table,array(
			"fields"=>"$fieldVal",
			"cond"=>"$field='$val'",
			)
		);
		if($this->getCount()>=1){
			return $find;
		}else{
			return 0;
		}
	}
	
	
	/**
	 * Retourne plusieurs enregistrements
	 * @param $fieldVal : champs à récupérer
	 * @param $val : valeur du champs de la condition
	 * @param $field : champs de la condition
	 * @return PDOStatement Object
	 */
	public function read_all($fieldVal,$val,$field=null){
		if(!isset($field)){ $field=$this->primary_key; }
		$find = $this->select($this->table,array(
			"fields"=>"$fieldVal",
			"cond"=>"$field='$val'",
			"type"=>"null"
			)
		);
		return $find;
	}
	
	
	/**
	 * Insert ou met à jour un enregistrement
	 * @param $data : tableau de données
	 * @param $val : valeur du champs de la condition
	 * @param $field : champs de la condition
	 */
	public function save($data,$val=null,$field=null){
		foreach($data as $k => $v){
			$data[$k]=addslashes(htmlspecialchars($v));
		}
		if(isset($val)){
			if(!isset($field)){ $field=$this->primary_key; }
			$this->update($this->table,$data,"$field='$val'");
		}else{
			$this->insert($this->table,$data);
		}
	}
}
?>