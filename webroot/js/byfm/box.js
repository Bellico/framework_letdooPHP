function box(options){
	
	var params={
		width:600,
		height:600,
		url:null,
		selector:"",
		scroll:true,
		move:false,
		resize:false,
		applat:true,
		css_applat:{position:"fixed",width:"100%",height:"100%",background:"#000",top:0,left:0,"z-index":1000},
		css_box:{position:"absolute",width:this.width,height:this.height,"z-index":1001,"overflow":"auto","border-radius":20,"box-shadow":"0 0 10px 10px #000",border:"1px solid #FFF"},
		css_btClose:{width:50,height:50,position:"absolute",top:0,right:0,cursor:"pointer",margin:6},
		css_btResize:{width: 30,height:30,position:"absolute",right:0,cursor: "nw-resize"}
	};
	
	var layerX;
	var layerY;
	
	/*Init*/
	if(options){$.extend(params,options)}
	
	var box=document.createElement("div");
	var bt_close=document.createElement("div");
	if(params.applat){
		var applat=document.createElement("div");
		$(applat).css(params.css_applat);
		$(applat).hide();
	}
	if(params.resize){
		var bt_resize=document.createElement("div");
		$(bt_resize).css(params.css_btResize);
		$(bt_resize).css({top:params.height-30});
		$(bt_resize).addClass("btResize");
	}
	$(box).addClass("fm_box").hide();
	$(bt_close).addClass("bt_closeBox");
	$(box).css(params.css_box);
	$(bt_close).css(params.css_btClose);
	
	if(params.url!=null){
		ajax(params.url,function(data){
			$(box).append(data);
		});
	}else{
		$(box).append($(params.selector));
	}

	/***
	 *Events
	 */
	if(!params.move){
		addEvent(window, 'resize',centerBox);
		addEvent(window, 'scroll',centerBox);
	}else{
		addEvent(box,'mousedown',function(e){
			layerX=e.layerX;
			layerY=e.layerY;
			addEvent(window,'mousemove',moveBox);
		})

		addEvent(box,'mouseup',function(e){
			delEvent(window,'mousemove',moveBox);
		})
	}
	
	if(params.resize){
		addEvent(bt_resize,'mousedown',function(e){
			layerX=e.layerX;
			layerY=e.layerY;
			addEvent(window,'mousemove',resize);
		})

		addEvent(bt_resize,'mouseup',function(e){
			delEvent(window,'mousemove',resize);
		})
	}
	
	if(params.applat){
		addEvent(applat,'click',close);
	}
	addEvent(bt_close,'click',close);
	addEvent(document,'keyup',function(e){
		if(e.keyCode==27){
			close();
		}
	})
	
	
	/*Run*/
	if(params.resize){
		box.appendChild(bt_resize);
	}
	box.appendChild(bt_close);
	if(params.applat){
		document.body.appendChild(applat);
	}
	document.body.appendChild(box);
	centerBox();
	showElem();
	
	
	/**
	 *Methodes
	 */
	this.show=function(){
		showElem();
	}
	
	this.close=function(){
		close();
	}
	
	this.resize=function(bool){
		if(bt_resize){
			if(bool){
				$(bt_resize).show();
			}else{
				$(bt_resize).hide();
			}
		}
	}


	/***
	 * Functions
	 */
	function centerBox(){
		var centerTop=(($(window).height() - params.height) / 2) + $(window).scrollTop();
		var centerLeft=(($(window).width() - params.width) / 2) + $(window).scrollLeft();
		$(box).css({"top":centerTop,"left":centerLeft});
	}
	
	function moveBox(e){
		var centerTop=e.clientY-layerY;
		var centerLeft=e.clientX-layerX;
		$(box).css({"top":centerTop,"left":centerLeft});
	}
	
	function resize(e){
		delEvent(window,'mousemove',moveBox);
		var centerTop=parseInt($(box).css("top"));
		var centerLeft=parseInt($(box).css("left"));
		var top=e.clientY-centerTop-layerY;
		var left=e.clientX-centerLeft-layerX;
		if(top>50){
			$(bt_resize).css({"top":top});
			$(box).css({"height":top+30});
			params.height=top+30;
		}
		if(left>50){
			$(bt_resize).css({"left":left});
			$(box).css({"width":left+30});
			params.width=left+30;
		}
	}
	
	function showElem(){
		$(box).css({"width":params.width,"height":0}).fadeIn(500);
		if(params.applat){
			$(applat).fadeTo(500,0.70,function(){
			$(params.selector).fadeIn("slow");
			var selectorHeight = parseInt($(params.selector).css("height"));
			if(selectorHeight>params.height && !params.scroll){
				params.height=selectorHeight;
			}
			$(box).animate({"height":params.height},500);
			});
		}else{
			$(params.selector).fadeIn("slow");
			var selectorHeight = parseInt($(params.selector).css("height"));
			if(selectorHeight>params.height && !params.scroll){
				params.height=selectorHeight;
			}
			$(box).animate({"height":params.height},500);
		}
	}
	
	function close(){
		$(box).fadeOut('slow');
		$(applat).fadeOut('slow');
	}

}
