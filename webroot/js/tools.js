/**
 * URL SERVEUR
 */
var url_server="http://localhost/Projets/LetDooPHP/";
//var url_server="http://bellico.1allo.com/";
//var url_server="http://"+window.location.host;

function addEvent(element, event, func){
	if (element.addEventListener){
		element.addEventListener(event, func, false);
    }else{ 
		element.attachEvent('on' + event, func);
    }
}

function delEvent(element, event, func){
	if (element.removeEventListener){
		element.removeEventListener(event, func, false);
    }else{ 
		element.detachEvent('on' + event, func);
    }
}

function getXMLHttpRequest() {
	var xhr = null;
	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest(); 
		}
	} else {
		return null;
	}
	return xhr;
}

function ajax(url,func){
	var xhr = getXMLHttpRequest();
	xhr.open("GET",url_server+url, true);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			func(xhr.responseText);
		}
	};
	xhr.send(null);
}

function jqAjax(url,func,data,dataType,method){
	if(!method){method="post";}
	if(!dataType){dataType="json";}
	$.ajax({
		url:url_server+url,
		type:method,
		dataType:dataType,
		data:data,
		success:function(d){
			func(d);
		}
	})
}

function l(variable){
	console.log(variable);
}

function arraySearch(val,arr){
	for (var i in arr){
		if (arr[i] == val){
			return i;
		}
	}
	return false;
}

function inArray(val,arr) {
    for (var i in arr){
        if(arr[i] == val) {
            return true;
        }
    }
    return false;
}