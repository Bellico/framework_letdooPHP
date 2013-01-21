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
 * Class Core
 * 
 * Lance l'application et charge les différents modules
 */
class Core{
	
	/**
	 * Initialise et charge les modules
	 */
	static public function RunApplication(){
		self::loader();
		Rooter::setRoot();
		Rooter::loadController(Rooter::parseRequest());
	}
	
	
	/*
	 * Modules à charger
	 */
	static private function loader(){
		session_start();
		require_once "config.php";
		require_once "tools.php";
		require_once "database.php";
		require_once "model.php";
		require_once "rooter.php";
		require_once "controller.php";
		return true;
	}
	
	
	/**
	 *Affiche un message d'erreur en mode debug
	 * @param $text : Message à afficher
	 * @param $fatalError : Vrai si le script doit être arrêté
	 */
	static public function defineError($text,$fatalError=true){
		$styleError="width:100%;text-align:center;line-height:50px;background:#F57900;color:#000;font-weight: bold";
		if(Config::$debug){
			echo("<div style='".$styleError."'>".$text."</div>");
			if($fatalError){die();}
			return true;
		}else{
			if($fatalError){
				die("Nous sommes désolé : Une erreur serveur est survenue. Veuillez patienter le temps que nous réglons le problème. Merci.");
			}
			return false;
		}
	}
	
	
	/**
	 *Affiche un message d'information en mode debug
	 * @param $text : Message à afficher
	 */
	static public function defineInfo($text){
		$styleInfo="width:100%;text-align:center;line-height:50px;background:#097721;color:#000;font-weight: bold";
		if(Config::$debug){
			echo("<div style='".$styleInfo."'>".$text."</div>");
		}
	}
}
?>