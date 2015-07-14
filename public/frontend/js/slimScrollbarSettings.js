	
(function($){	   
	$(".scroll_pan").slimScroll({		
			alwaysVisible: true,				
			railVisible: true,
			railColor: '#222',
			railOpacity: 0.3,				
			allowPageScroll: false,
			disableFadeOut: false			
		});
		
	$(window).load(function(){
		$(".jssorb18 div[u='prototype']").css({"left":"","position":"","float":"left","margin-right":"5px","width":""});
		
	
	});			   
   
	
	$(".panel-heading").click(function(){ console.log("00000");
		
		$(this).next(".panel-collapse").find(".mCSB_scrollTools").css({"position": "absolute", "display": "block"});
		$(this).next(".panel-collapse").find(".mCSB_container").css({"position": "relative", "top": "0px"});
	});
})(jQuery);