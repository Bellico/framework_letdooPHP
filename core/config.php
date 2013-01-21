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
 * Class Config
 * Définit vos paramètres personnel
 */
class Config{
	
	/**
	 * Donner un nom à votre site/appli Web
	 * Apparait dans la balise title
	 */
	
	static $nameAppli="LetDoo Me";
	
	/**
	 * Le layout par défaut est le modele (le thème) sur lequel reposerons toutes vos pages/vues
	 */
	static $layout="letdoo";
	
	
	/**
	 * Vous pouvez regrouper vos controllers et vues dans des dossiers
	 * Il faut alors préciser : le nom d'accés en URL => le nom dossier
	 */
	static $prefix=array(
		"error"=>"error"
	);
	
	
	/**
	 * Information de connexion aux bases de donnée
	 * Plusieurs bases peuvent etre utilisées
	 * Définissez la base défault (celle utilisée par défaut)
	 * 
	 * Il est conseillé de creer la base en UTF8 pour éviter tous problèmes d'encodage.
	 */
	
	static  $baseInfos=array(
		"default"=>array(
			"server"=>"localhost",
			"dbname"=>"letdoo",
			"login"=>"root",
			"password"=>""
			)
		);

	
	/**
	 * Le mode debug permet de vous guider lors du développement
	 * Il permet nottament de : 
	 * afficher les variables envoyées par le controller sur la view
	 * generer des erreurs personalisées
	 * 
	 *  Désactiver le, lors du déploiement de l'application sur un hébergeur
	 */
	static $debug=true;
	
}
?>
