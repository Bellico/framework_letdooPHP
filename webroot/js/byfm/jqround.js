(function($){
	jQuery.fn.round = function(options){
		
		var params={
			tab:[],			//tableau des élements html à l'intérieur du conteneur
			angle:[],		//tableau de l'angle ou est positioné actuellement l'éléments html correspondant 
			speed:1,		//vitesse de rotation, une vitesse négative, inverse le sens de rotation
			space:360,		//l'angle qui séprare chaques élements
			centerX:0,		//centre X du conteneneur
			centerY:0,		//centre Y du conteneneur	
			top:0,			//postion par rapport au top
			left:0,			//postion par rapport au left
			pos:0,			//position de départ du 1er élément
			decX:0,			//decalalge horizontale
			decY:0,			//decalalge verticale
			degre:360,		//Mouvement par défaut pour move()
			max:0,
			timer:0
		};
		
		if(options){$.extend(params,options)}
		var elements=$(this).children();
		var j=0;
		//On recupere les élements dans un tableau et on initialise leur angle à 0
		for (var i = 0; i< elements.length; i++) {
			if (elements[i].nodeType === 1) { 
				elements[i].style.position="absolute";
				params.tab[j]=elements[i];
				params.angle[j]=0;
				j++;
			}
		}
		//Calcule de l'espace entre chaque élements
		params.space= 360 / params.tab.length;
		//Définiton de l'angle des élements par rapport à l'espace
		for (i= 0; i< params.tab.length; i++) {
			params.angle[i]=params.pos+params.space*i;
		}
		//Définit le centre du conteneur
		params.centerX = parseInt($(this).css("width"))/2;
		params.centerY =parseInt($(this).css("height"))/2;
		//Et les coordonnées
		params.top=($(this).offset().top);
		params.left=($(this).offset().left);
		
		if(params.play){play();}else {newPoint();}
		
		if(params.move){
			$(params.move).click(function(e){
				e.preventDefault();
				move();
			})
		}
		
		if(params.stop){
			$(params.stop).click(function(e){
				e.preventDefault();
				stop();
			})
		}
		
		
		/**
		 * Lance l'animation
		 */
		function play(){
			params.timer = setInterval(newPoint,1);
		}
	
		
		/**
		 * Calcule les coordonnées d'un point du cercle pour un angle et y positione les élements
		 */
		function newPoint(){
			for (var i = 0; i< params.tab.length; i++) {
				var elem=params.tab[i];
				params.angle[i]+=params.speed;
				var w=elem.offsetWidth;
				var h=elem.offsetHeight;
				var posX = params.centerX - w + (params.centerX * Math.cos((params.angle[i]) * Math.PI / 180))+params.left;
				var posY =params.centerY - h + (params.centerY * Math.sin((params.angle[i]) * Math.PI / 180)+params.top); 
				
				//alert (posX+"--"+posY);
				elem.style.left = (posX+ w + params.decX)+"px"; 
				elem.style.top = (posY+ h +params.decY)+"px"; 
			}
			if(params.max!=0){
				if(params.speed>0){
					if(params.angle[0]>=params.max){clearInterval(params.timer);}
				}else{
					if(params.angle[0]<=params.max){clearInterval(params.timer);}
				}
			}
		}
		
		
		/**
		 * Stop l'animation
		 */
		function stop(){
			clearInterval(params.timer)
		}
		
		
		/**
		 * Bouge les élements d'un degre définit
		 */
		function move(){
			clearInterval(params.timer)
			params.timer = setInterval(newPoint,1);
			if(params.speed>0){
				params.max=params.angle[0]+params.degre;
			}else{
				params.max=params.angle[0]-params.degre;
			}
		}

	};
})(jQuery)