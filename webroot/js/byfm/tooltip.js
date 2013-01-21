(function($){
	jQuery.fn.tooltip = function(options){
		
		var params={
			css:"tooltip",
			attr:"title",
			decX:10,
			decY:10
		};
		
		if(options){$.extend(params,options)}
		var element=document.createElement("div");
		$(element).addClass(params.css).hide();
		document.body.appendChild(element);

		return this.each(function(){
			var title=$(this).attr(params.attr);
			$(this).hover(function(){
				$(element).fadeIn('slow');
				$(element).html(title);
				$(this).attr("title","");
				$(this).mousemove(function(e){
					$(element).css({
						"position":"absolute",
						"top":e.pageY+params.decY,
						"left":e.pageX+params.decX
					});
				});
			}
			,function(){
				$(element).hide();
				$(this).attr("title",title);
			});
		});
	};
})(jQuery)