<?php
class DefaultController extends Controller{
	
	function action_start(){
		$this->setTitle("Bienvenue sur LetDoo PHP");
		$this->clean_headerElements();
	}
}
?>