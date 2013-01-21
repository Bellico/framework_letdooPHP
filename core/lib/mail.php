<?php
class Mail{
	
    private $headers="";

	public function __construct() {
		
	}
	
	public function send_mail($data){
		 mail($data["to"],$data["subject"],$data["message"],$this->headers);
	}
}
?>
