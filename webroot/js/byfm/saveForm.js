jQuery(function($){
	$.fn.saveForm= function(){
		if(localStorage){
			var datas = {
				href:window.location.href
			}

			if(localStorage[window.location.href]){
				ls=JSON.parse(localStorage[window.location.href]);
				if(ls.href==datas.href){
					for(var i in ls){
						if(i!="href"){
							$(this).find("[name="+i+"]").val(ls[i]);
						}
					}
				}
			}
			
			$(this).find("input,textarea").keyup(function(){
				datas[$(this).attr("name")]=$(this).val();
				localStorage.setItem(window.location.href,JSON.stringify(datas));
			})
			
			$(this).submit(function(){
				localStorage.removeItem(window.location.href);
			})
		}
	}
})
