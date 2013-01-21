<?php
class Tools{
	
	static public function token($hash=null){
		if(isset($hash)){
			return md5(time()*rand(358,846).$hash);
		}else{
			return md5(time()*rand(358,846));
		}
	}
	
	static public function slug($string){
		$s=explode(" ",$string);
		$slug="";
		foreach($s as $v){
			if(strlen($v)>2){
				$slug.=$v."-";
			}
		}
		$slug=substr($slug, 0,-1);
		return $slug;
	}
	
	static public function post($data){
		foreach ($data as $v){
			if(!isset($_POST[$v])){
				return false;
			}
		}
		return true;
	}
	
	static public function img($width,$height){
		echo '<img src="http://lorempixel.com/'.$width.'/'.$height.'" alt="" />';
	}

	static public function imgCat($width,$height){
		echo '<img src="http://placekitten.com/'.$width.'/'.$height.'" alt="" />';
	}
	
	static public function imgCatG($width,$height){
		echo '<img src="http://placekitten.com/g/'.$width.'/'.$height.'" alt="" />';
	}
	
	static public function DateToString($date){
		$tabmois=array("null","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
		$tabJour=array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi");
		list($dateformate, $h) = explode(" ", $date);
		list($heure,$minute) = explode(":", $h);
		list($year, $month, $day) = explode("-", $dateformate);
		if($month[0]=="0"){$month=substr($month,1);}
		$jour = date("w", mktime(0, 0, 0, $month, $day, $year));
		return "le $tabJour[$jour] $day $tabmois[$month] $year à $heure:$minute";
	}
	
	static public function DateToTime($date){
		$n= strtotime($date);
		$date=date('Y-m-j H:i:s');
		$h=date('H')+2;
		$date= date('Y').'-'.date('m').'-'.date('j').' '.$h.':'.date('i').':'.date('s');
		$d= strtotime($date);
		$inter=$d-$n;
		$time=0;

		if($inter<60){
			$time=($inter>1)? $inter.=" secondes":$inter.=" seconde";
		}else
		if($inter<3600){
			$time=floor($inter/60);
			$time=($time>1)? $time.=" minutes":$time.=" minutes";
		}else
		if($inter<86400){
			$time=floor($inter/3600);
			$time=($time>1)? $time.=" heures":$time.=" heure";
		}else
		if($inter<2592000){
			$time=floor($inter/86400);
			$time=($time>1)? $time.=" jours":$time.=" jour";
		}else
		if($inter<31536000){
			$time=floor($inter/2592000)." mois";
		}else{
			$time=floor($inter/31536000);
			$time=($time>1)? $time.=" ans":$time.=" an";
		}
		return "il y a ".$time;
	}
	
}
?>
