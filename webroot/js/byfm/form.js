(function($){
	jQuery.fn.form = function(data){

		var controls=new Array;
		controls["name"]=["^[A-Za-zéèç-]{2,15}$","Nom Invalide."];
		controls["mail"]=["^[a-z]+[a-z0-9._-]*@(hotmail|orange|laposte|yahoo|live|gmail|free|(ac-[a-z]+(-[a-z]+)*)).(net|fr|org|com)$","Email Invalide."];
		controls["login"]=["^[A-Za-z0-9@ øéèÉà_-]{3,13}$","Login incorrect."];
		controls["message"]=["^(.){20,}$","Message Invalide."];

		$(this).submit(function(e){
			$(".infoError").remove();
		
			var error=false;
			error=Control_Password(data);
			
			for (var i in data){
				var input = $("input[name="+i+"]");	
				if(input.val()!=""){
					if(data[i]!="password" && data[i]!="C_password"){
						if(input.val().match(controls[data[i]][0])){
							input.css("border-color","green");
						}else{
							input.css("border-color","red");
							input.parent().after('<td class="infoError">'+controls[data[i]][1]+'</td>');
							error=true;
						}
					}
				}else{
					input.css("border-color","red");
					input.parent().after('<td class="infoError"> Veuillez remplir ce champs.</td>');
					error=true;
				}
			}
			var formError=$(".infoError");
			formError.hide();
			formError.fadeIn();
			if(error){
				e.preventDefault();
			}
		})	
		
		function Control_Password(data){
			var error=false;
			if(inArray("password",data) && inArray("C_password",data)){
				var input = $("input[name="+arraySearch("password",data)+"]");	
				var inputC = $("input[name="+arraySearch("C_password",data)+"]");	
				if(inputC.val()!=""){
					if(input.val()!=inputC.val()){
						error=true;
						inputC.css("border-color","red");
						inputC.parent().after('<td class="infoError">Mot de passe non valide</td>');
					}else{
						input.css("border-color","green");
						inputC.css("border-color","green");
					}
				}
			}
			return error;
		}
	}
})(jQuery)

/*
	jQuery(function($){
		var tab=new Array
		tab["nameUser"]="name";
	    tab["firstnameUser"]="name";
		tab["loginUser"]="login";
		tab["mailUser"]="mail";
		tab["passwordUser"]="password";
		tab["C_password"]="C_password";

		$("#formulaire_1").form(tab);
	});
*/