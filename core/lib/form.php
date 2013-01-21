<?php 
class Form{
	
	/**
	 * Nombre de formulaire par page
	 */
	static private $nb=0;
	
	private $controls=array(
		"mail"=>array(
			"regex"=>"#^[a-z]+[a-z0-9._-]*@(hotmail|orange|laposte|yahoo|live|gmail|free|(ac-[a-z]+(-[a-z]+)*)).(net|fr|org|com)$#",
			"message"=>"Email Invalide."
		),
		"name"=>array(
			"regex"=>"#^[A-Za-zéèç-]{2,15}$#",
			"message"=>"Nom Invalide."
		),
		"login"=>array(
			"regex"=>"#^[A-Za-z0-9@ øéèÉà_-]{3,13}$#",
			"message"=>"Login incorrect."
		),
		"message"=>array(
			"regex"=>"#^(.){20,}$#",
			"message"=>"Message Invalide."
		),
		"shortText"=>array(
			"regex"=>"#^(.){10,50}$#",
			"message"=>"Saisie Invalide."
		),
		"numeric"=>array(
			"regex"=>"#^[0-9]+$#",
			"message"=>"Saisie invalide (que des chiffres)."
		)
	);
	
	private $errors=array();

	public function getError(){
		return sizeof($this->errors);
	}
	
	/**
	 * Cree un formulaire
	 * @param $action : adresse ou s'execute le script
	 * @param $fields : tableau représentant les champs du formulaire (name=label=>type=value)
	 * @param $attr : tableau des attributs de la balise <form>
	 * @param $displayError : affichage des erreurs => 0 = horizontale , 1 = verticale
	 * @param $displayForm : affichage des labels => 0 = horizontale , 1 = verticale
	 * @return boolean
	 */
	public function createForm($action,$fields,$attr=null,$displayError=0,$displayForm=0){
		self::$nb++;
		$action=Rooter::getUrl($action);
		$form= '<form ';
		if(in_array("file",$fields)){$form.=' enctype="multipart/form-data" ';}
		$attr_default=array(
			"id"=>"formulaire_".self::$nb,
			"method"=>"post",
		);
		$attr_default["action"]=$action.="#".$attr_default["id"];
		if(isset($attr)){$attr+=$attr_default;}else{
			$attr=$attr_default;
		}
		foreach($attr as $k => $v){
			$form.=$k.'="'.$v.'" ';
		}
		$form.='> <table>';
		foreach ($fields as $k => $v) {
			$name_label=explode("=",$k);
			if(!is_array($v)) { $type_value=explode("=",$v); }else{$type_value[0]="select"; }
			if(!isset($type_value[1])) { $type_value[1]=null; }
			if(isset($_POST[$name_label[0]])) { $type_value[1]=$_POST[$name_label[0]] ; }
			
			$form.='<tr>';
			$form.='<td>';
			$form.=(isset($name_label[1]) && $name_label[0]!="null") ? '<label for="L_'.$name_label[0].'"> '.$name_label[1].' : </label>' : '';
			$form.='</td>';
			
			if($displayForm==0){
				$form.='<td>';
			}else{
				$form.='</tr> <tr> <td>';
			}
			
			if($type_value[0]=="textarea"){
				$form.=$this->textarea($name_label[0],$type_value[1]);
			}elseif($type_value[0]=="select"){
				$form.=$this->select($name_label[0],$v);				
			}else{
				$form.=$this->input($name_label[0],$type_value[0],$type_value[1]);
			}
			$form.='</td>';
			if(isset($this->errors[$name_label[0]])){
				if($displayError==0){
					$form.='<td class="infoError">'.$this->errors[$name_label[0]].'</td>'; 	
					$form.='</tr>';
				}else{
					$form.='</tr> <tr>';
					if($displayForm==0){$form.='<td></td>';}
					$form.='<td class="infoError">'.$this->errors[$name_label[0]].'</td> </tr>'; 
				}
			}else{
				$form.='</tr>';
			}
		}
		$form.='</table></form>';
		echo $form;
		return true;
	}
	
	/**
	 * Cree un champs <input>
	 * @param $name : name
	 * @param $type : type
	 * @param $value : value
	 * @return String 
	 */
	public function input($name,$type,$value){
		if(isset($value)){ $value='value="'.$value.'"'; }else{ $value=''; }
		if($name!="null"){
			return '<input id="L_'.$name.'" name="'.$name.'" type="'.$type.'" '.$value.' />';
		}else{
			return '<input type="'.$type.'" '.$value.' />';
		}
	}
	
	/**
	 * Cree un champs <textarea>
	 * @param $name : name
	 * @param $value : value
	 * @return String 
	 */
	public function textarea($name,$value){
		return '<textarea  id="L_'.$name.'" name="'.$name.'">'.$value.'</textarea>';
	}
	
	/**
	 * Cree un champs <select>
	 * @param $name : name
	 * @param $opt : tableau <option>
	 * @return String
	 */
	public function select($name,$opt=array()){
		$s='<select id="L_'.$name.'" name="'.$name.'">';
		foreach ($opt as $k=>$v ) {
			$s.='<option value="'.$k.'">'.  ucfirst($v).'</option>';
		}
		$s.='</select>';
		return $s;
	}
	
	public function checkData($data){
		$error=$this->Control_Password($data);
		foreach ($data as $k =>$v) {
			if(isset($_POST[$k]) && $_POST[$k] != ""){
				if ($v!= null && !preg_match($this->controls[$v]["regex"],$_POST[$k])){
					if(isset($this->controls[$v]["message"])){
						$this->errors[$k]=$this->controls[$v]["message"];
					}else{
						$this->errors[$k]="Votre saisie est invalide.";
					}	
					$error++;
				}
			}else{
				$this->errors[$k]="Veuillez remplir ce champs.";
				$error++;
			}
		}
		return $error;
	}
	
	public function addError($name,$error){
		$this->errors[$name]=$error;
	}
	
	
	public function formConect($action,$login="login",$pass="password"){
		$this->createForm($action,array(
			$login."=Login"=>"text",
			$pass."=Mot de passe"=>"password",
			"null"=>"submit=Se connecter"
		));
	}
	
	private function Control_Password(&$data){
		$error=0;
		if(in_array("password", $data) && in_array("C_password", $data)){
			$pass=array_search("password",$data);
			$passConfirm=array_search("C_password",$data);
			if($_POST[$pass]!=$_POST[$passConfirm]){
				$this->errors[$passConfirm]="Mot de passe non valide.";
				$error++;
				$data[$pass]=null;
				unset($data[$passConfirm]);
			}else{
				$data[$pass]=null;
				$data[$passConfirm]=null;
			}
		}
		return $error;
	}
	
}
?>