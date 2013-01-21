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
 * Class Controller
 * 
 * Initialise les variables et traitements
 * Puis charge une vue en lui envoyant les données
 */
abstract class Controller{
	
	/**
	 * L'action a appellée
	 */
	protected $action;
	
	
	/**
	 * Paramètres facultatifs qui accompagent l'action
	 */
	protected $params;
	
	
	/**
	 * Détermine si on affiche les header
	 */
	private $header=true;
	
	
	/**
	 * Layout sur lequel repose la vue
	 */
	private $layout;
	
	
	/**
	 * Librairies utilisées au sein du controller 
	 */
	private $libUsing=array();
	
	
	/**
	 * Model de table chargés au sein du controller 
	 */
	private $modelUsing=array();
	
	
	/**
	 * Données envoyées à la vue
	 */
	private $varView=array();
	
	
	/**
	 * Eléments (js/css) attachés à la page
	 */
	private $headerElements=array(
		"style"=>"css",
		"jquery/jquery"=>"js",
		"tools"=>"js"
	);
	
	
	/**
	 * Appelle l'action demandée et charge la vue
	 * @param $dataRequest : données parsés par le rooter
	 */
	public function __construct($dataRequest=array()){
		$this->prefix=$dataRequest["prefix"];
		$this->controller=$dataRequest["controller"];
		$this->view=$dataRequest["view"];
		$this->action=$dataRequest["action"];
		$this->params=$dataRequest["params"];	
		$this->set(ucfirst($dataRequest["controller"])." | ".Config::$nameAppli,"titlePage");
		$this->layout=Config::$layout;

		if(method_exists($this,"action_start")){$this->action_start();}
		if($dataRequest["action"]!=null){
			if(method_exists($this,$dataRequest["action"])){
				$this->$dataRequest["action"]();
			}else {
				if(!Core::defineError("L'action '{$dataRequest["action"]}' n'est pas définie dans '{$dataRequest["controller"]}Controller'",false)){
					$this->error();
				}		
			}
		}else{if(method_exists($this,"default_action")){$this->default_action();}}
		if(method_exists($this,"action_end")){$this->action_end();}
		$this->debug();
		$this->loadView();
	}
		
	
	/**
	 * Charge la vue en envoyant les données
	 */
	private function loadView(){
		$v=Rooter::getView_root().$this->prefix.Rooter::DS.$this->view.".php";
		if(file_exists($v)){
			$this->set($v, "viewContent");
			extract($this->varView);
			if($this->header){
				$layout=Rooter::getView_root()."layouts".Rooter::DS.$this->layout.".php";
				if(file_exists($layout)){
					require_once $layout;
				}else{
					Core::defineError("Il n'existe pas de model de page : 'view/layouts/$this->layout'");
				}
			}else{
				require_once $v;
			}
			return true;
		}else{ 
			Core::defineInfo("Aucune vue associée : view$this->prefix/$this->controller.php ");
			return false;
		}
	}
	
	
	/**
	 * Affiche le mode debug en début de page
	 * Permet de visualiser les variables envoyées à la vue et les variables SESSION, GET , POST
	 */
	private function debug($elem=null){
		if(Config::$debug){
			echo "<div style='padding:5px; margin:10px;background:#FFF;border: 2px solid #000;color:#000'> DEBUG";
			if(isset($elem)){
				echo "<pre>";
				print_r($elem);
			}else{
				echo " [controller] => ".  $this->controller;
				echo " [view] => ".  $this->view;
				echo "<pre>";
				print_r($this->varView);
				if(!empty($_GET)){print_r($_GET);}
				if(!empty($_POST)){print_r($_POST);}
				if(!empty($_SESSION)){print_r($_SESSION);}
			}
			echo "</pre>";
			echo "</div>";
		}
	}
		
	
	/**
	 * Définit une nouvelle variable qui sera envoyée à la vue
	 * @param $var : valeur
	 * @param $val : nom 
	 */
	protected function set($var,$val="var_unknown"){	
		if(is_array($var)){
			if($val=="var_unknown"){
				$this->varView+=$var;
			}else{
				$this->varView[$val]=$var;
			}
		}else{
			if($val=="var_unknown"){$val="var_unknown_".substr(uniqid(),-3);}
			$this->varView[$val]=$var;
		}
	}
	
	
	/**
	 * Renvoi un objet Model ou instancie un nouveau 
	 * @param $table : table de la BDD
	 * @param $dbUsing : BDD à utilisée
	 * @return Objet type Model 
	 */
	protected function model($table=null,$dbUsing=null){
		if(!isset($this->modelUsing[$table])){
			$this->modelUsing[$table]=new Model($table,$dbUsing);
		}
		return $this->modelUsing[$table];
	}
	
	
	/**
	 * Instancie un nouveau model personnel
	 * @param $model : nom du model
	 */
	protected function loadmodel($model){
		if(!isset($this->$model)){
			$path= Rooter::getModel_root().$model.".php";
			if(file_exists($path)){
				require_once $path;
				$call_Class=ucfirst($model);
				$this->$model=new $call_Class();	
			}else{
				Core::defineError("La model '$model' n'existe pas.");
			}
		}
		return $this->$model;
	}
	
	
	/**
	 * Renvoi un objet correspondant à la class librairie visée ou instancie un nouveau 
	 * @param $class : class librairie
	 * @param $arg : paramètres éventuels
	 * @return Objet 
	 */
	protected function using($class,$arg=null){
		if(!isset($this->libUsing[$class])){
			$path= Rooter::getCore_root()."lib".Rooter::DS.$class.".php";
			if(file_exists($path)){
				require_once $path;
				$call_Class=ucfirst($class);
				$this->libUsing[$class]=new $call_Class($arg);		
			}else{
				Core::defineError("La librairie '$class' n'existe pas.");
			}
		}
		return $this->libUsing[$class];
	}
	
	
	/**
	 * Modifie le titre de la page HTML
	 * @param $title : nouveau titre
	 */
	protected function setTitle($title){
		$this->set($title,"titlePage");
	}
	
	
	/**
	 * Définie une nouvelle vue à afficher
	 * @param $view  : vue
	 */
	protected function setView($view){
		$this->view=$view;
	}
	
	
	/**
	 * Définie une nouveau layout pour la vue
	 * @param $layout  : layout
	 */
	protected function setLayout($layout){
		$this->layout=$layout;
	}
	
	
	/**
	 * Retourne un lien formaté du la vue ciblée
	 * @param $link : nom de la vue
	 * @return type 
	 */
	protected function getView($name){
		return Rooter::getView_root().$name.".php";
	}
	
	/**
	 * Retourne un lien formaté du layout ciblé
	 * @param $name : nom du layout
	 * @return type 
	 */
	protected function getLayout($name){
		return Rooter::getView_root()."layouts".Rooter::DS.$name.".php";
	}
	
	/*
	 * Permet de charge directement la vue sans le layout
	 */
	protected function no_header(){
		$this->header=false;
		Config::$debug=false;
	}
	
	
	/*
	 * Rederige vers une page
	 * @param $link  : lien
	 */
	protected function redirect($link){
		header("Location:".Rooter::getUrl($link)."");
	}
	
	
	/**
	 * Définit un nouvel élement externe (js ou css) attaché à la page
	 * @param $elements : tableau d'élément "nom"=>"type"
	 */
	protected function add_headerElements($elements=array()){
		$this->headerElements+=$elements;
	}
	
	/**
	 * Supprime tous les élements externes (js ou css) attachés à la page
	 * @param $elements : tableau d'élément "nom"=>"type"
	 */
	protected function clean_headerElements(){
		$this->headerElements=array();
	}
	
	
	/**
	 * Affiche les liens formatés des élements externe
	 */
	private function link_headerElements(){
		foreach ($this->headerElements as $k => $v){
			if($v=="css"){
				echo '<link href="'.Rooter::getUrl("css/$k.css").'" rel="stylesheet" type="text/css" />';
			}
			else if ($v=="js"){
				echo '<script type="text/javascript" src="'.Rooter::getUrl("js/$k.js").'"></script>';
			}
		}
	}
	
	/**
	 * Affiche les liens formatés des élements css
	 */
	private function css(){
		foreach ($this->headerElements as $k => $v){
			if($v=="css"){
				echo '<link href="'.Rooter::getUrl("css/$k.css").'" rel="stylesheet" type="text/css" />';
			}
		}
	}
	
	/**
	 * Affiche les liens formatés des élements js
	 */
	private function js(){
		foreach ($this->headerElements as $k => $v){
			if ($v=="js"){
				echo '<script type="text/javascript" src="'.Rooter::getUrl("js/$k.js").'"></script>';
			}
		}
	}
	
	/**
	 * Affiche la vue error et affiche un message
	 * @param $mess : message
	 */
	protected function error($mess=null,$page="default"){
		$this->setView("error/$page");
		$this->set($mess,"errorMessage_ViewError");
		$this->loadView();
		exit();
	}
		
	
	/**
	 * Définit une page de type ADMIN
	 */
	protected function admin(){
		if(!isset($_SESSION["admin"])){
			$this->setLayout("adminlog");
			$this->loadView();
			exit();
		}	
	}
}
?>