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
 * Class Rooter
 * 
 * Gere les routes et les URL
 */
class Rooter{
	
	const DS=DIRECTORY_SEPARATOR;
	const HOME_PAGE="index";
	const DEFAULT_PAGE="default";
	const ERROR_PAGE="error";
	
	/**
	 * URL du serveur 
	 */
	static private $http_root;
	
	
	/**
	 * Prefixes des dossiers autorisés
	 */
	static private $prefix=array();
	
	/**
	 * Racine dossier Application
	 */
	static private $webroot;
	
	
	/**
	 * Adresse absolue du dossier core
	 */
	static private $core_root;
	
	
	/**
	 * Adresse absolue du dossier view
	 */
	static private $view_root;
	
	
	/**
	 * Adresse absolue du dossier controller
	 */
	static private $controller_root;
	
	
	/**
	 * Adresse absolue du dossier model
	 */
	static private $model_root;
	
	
	/**
	 * Parse l'URL entrée et définit le controller et l'action à appeler
	 * @return (array) Tableau prefix/controller/action/paramètres
	 */
	static public function parseRequest(){
		$request=$_SERVER['REQUEST_URI'];
		if(self::$webroot != "/"){
			$request=str_replace(self::$webroot,"",$request); 
		}else{
			$request=substr($request,1); 
		}
		$n=strpos($request,"q=");
		if($n>0){$request=substr($request,0,$n); }
		$arg=explode("/",$request);
		$data=array();
		if(isset($arg[0]) && array_key_exists($arg[0],self::$prefix)){
			$data["prefix"]=self::$prefix[$arg[0]];
			array_shift($arg);
		}else{
			$data["prefix"]=null;
		}
		if(isset($arg[0]) && $arg[0]!=""){
			$data["controller"]=$arg[0];
			$data["view"]=$arg[0];
		}else{
			$data["controller"]=self::HOME_PAGE;
			$data["view"]=self::HOME_PAGE;
		}
		(isset($arg[1]) && $arg[1]!="") ? $data["action"]=$arg[1]: $data["action"]=null;
		(isset($arg[2]) && $arg[2]!="") ? $data["params"]=  array_slice($arg, 2): $data["params"]=null;
		return $data;
	}
	
	
	/**
	 * Charge un controller et lui envoi les données parsés de l'URL
	 * @param $dataRequest : données url parsés
	 * @return bool 
	 */
	static public function loadController($dataRequest){
		$root_path=self::$controller_root.$dataRequest["prefix"].self::DS;
		$path =$root_path.$dataRequest["controller"]."Controller.php";
		if(file_exists($path)){
			require_once $path;
			$call_Controller=ucfirst($dataRequest["controller"])."Controller";
			$controller = new $call_Controller($dataRequest);
		}else{
			if(Config::$debug){
				if($dataRequest["controller"]==self::HOME_PAGE){
					$path =$root_path.self::DEFAULT_PAGE."Controller.php";
					if(file_exists($path)){
						require_once $path;
						$dataRequest["controller"]=self::DEFAULT_PAGE;
						$dataRequest["view"]=self::DEFAULT_PAGE;
						$call_Controller=ucfirst(self::DEFAULT_PAGE)."Controller";
						$controller = new $call_Controller($dataRequest);
					}else{
						Core::defineError("Aucun controller '".$dataRequest["controller"]."'");
					}
				}else{
					Core::defineError("Aucun controller '".$dataRequest["controller"]."'");
				}
			}else{
				$dataRequest["prefix"]=self::ERROR_PAGE;
				$path =self::$controller_root.$dataRequest["prefix"].self::DS.self::DEFAULT_PAGE."Controller.php";
				if(file_exists($path)){
					require_once $path;
					$dataRequest["controller"]=self::DEFAULT_PAGE;
					$dataRequest["view"]=self::DEFAULT_PAGE;
					$controller = new DefaultController ($dataRequest);
				}else{
					Core::defineError("Aucun controller '".$dataRequest["controller"]."'");
				}
			}
		}
		return true;
	}
	
	
	/**
	 * Initialise les adresses root
	 */
	static public function setRoot(){
		if(isset($_SERVER["REDIRECT_VH_PATH"])){
			$path=$_SERVER["REDIRECT_VH_PATH"].self::DS; //$_SERVER["REDIRECT_SCRIPT_URL"]
		}else{
			$path=dirname(dirname(realpath("index.php"))).self::DS;
		}
		self::$prefix=Config::$prefix;
		self::$view_root=$path."view".self::DS;
		self::$controller_root=$path."controller".self::DS;
		self::$model_root=$path."model".self::DS;
		self::$core_root=$path."core".self::DS;
		self::$webroot=dirname(dirname($_SERVER['SCRIPT_NAME']));
		self::$http_root="http://".$_SERVER['HTTP_HOST'].self::$webroot;
		(substr(self::$http_root,-1)!='/') ? self::$http_root.="/":0;
		(substr(self::$webroot,-1)!='/') ? self::$webroot.="/":0;
		return true;
	}
	
	
	/**
	 * Ecrit directement une URL formatées 
	 * @param $root : liens du controller/action
	 * @param $get  : variables $_GET
	 */
	static public function url($root,$get=null){
		echo self::getUrl($root,$get);
	}
	
	
	/**
	 * Retourne une URL formatées 
	 * @param $root : liens du controller/action
	 * @param $get  : variables $_GET
	 */
	static public function getUrl($root,$get=null){
		$elem=explode("/",$root);
		$prefix=  array_search($elem[0],self::$prefix);
		if($prefix){$r=self::$http_root.str_replace($elem[0], $prefix, $root);}
		else{$r=self::$http_root.$root;}
		if(isset($get)){
			(substr($r,-1)!='/') ? $r.="/" : "";
			$r.="q=";
			foreach ($get as $k => $v){
				$r.="$k-$v/";
			}
		}
		return $r;
	}
	
	
	/**
	 ** Retourne l'adresse absolue du dossier core
	 */
	static public function getCore_root(){
		return self::$core_root;
	}
	
	
	/**
	 ** Retourne l'adresse absolue du dossier view
	 */
	static public function getView_root(){
		return self::$view_root;
	}
	
	
	/**
	 ** Retourne l'adresse absolue du dossier controller
	 */
	static public function getController_root(){
		return self::$controller_root;
	}
	
	
	/**
	 ** Retourne l'adresse absolue du dossier model
	 */
	static public function getModel_root(){
		return self::$model_root;
	}
	
}
?>