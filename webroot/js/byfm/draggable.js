(function($){
	jQuery.fn.draggable = function(){
		
		var layerX;
		var layerY;
		var elem=$(this);
		
		$(this).mousedown(function(e){
			$(this).css({position:"absolute"});
			layerX=e.clientX-parseInt($(this).css("left"));
			layerY=e.clientY-parseInt($(this).css("top"));
			$(window).mousemove(moveElem);
		})

		$(this).mouseup(function(){
			$(window).unbind("mousemove");
		})
	
		function moveElem(e){
			var centerTop=e.clientY-layerY;
			var centerLeft=e.clientX-layerX;
			elem.css({"top":centerTop,"left":centerLeft});
		}
	};
})(jQuery)