<?php
class Users extends Model{

	const INDEX="user";
	
	var $table="users";
	var $no_doublons="login";
	var $pass="password";
	var $hach="5GEeTUIt459sdfG578e47edEDazfztSr";

	public function add($infos,$session=true){
		$exist = $this->find($infos[$this->no_doublons],$this->no_doublons);
		if(!$exist){
			if($this->hach){$infos[$this->pass]=md5($infos[$this->pass].$this->hach);}
			$this->save($infos);
			if($session){
				$this->setSession($infos[$this->no_doublons]);
			}
			return true;
		}else{
			return false;
		}
	}
	
	public function login($log,$conditions=null){
		$login=current($log);
		$password=end($log);
		$key_log=array_search($login,$log); 
		$key_pass=array_search($password,$log); 
		
		$cond=array(
			$this->no_doublons=>$login
		);
		if(isset($conditions)){
			$cond+=$conditions;
		}
		$exist = $this->select($this->table,array(
			"fields"=>"COUNT(*) AS exist",
			"cond"=>$cond
			)
		);
		
		if($exist->exist>0){
			$mdp=$this->first($key_pass,$login,$key_log);	
			if($this->hach){$password=md5($password.$this->hach);}
			if($mdp->$key_pass == $password){
				$this->setSession($login);
				return 0;
			} else{
				return 1;
			}
		} else {
			return 2;
		}
	 }
	 	 
	 public function isLog(){
		 if(isset($_SESSION[Users::INDEX])){
			 return true;
		 }else{
			 return false;
		 }
	 }
	 
	 public function getUser($attr=null){
		 if(isset($attr)){
			 return $_SESSION[Users::INDEX]->$attr;
		 }else{
			  return $_SESSION[Users::INDEX];
		 }
	 }
	 	 
	public function logOut(){
		unset ($_SESSION[Users::INDEX]);
	}
	
	private function setSession($uniq){
		$_SESSION[Users::INDEX]=$this->first("*",$uniq,$this->no_doublons);
		$_SESSION[Users::INDEX]->token=Tools::token($this->hach);
	}
}
?>