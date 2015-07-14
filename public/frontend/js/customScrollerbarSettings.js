    (function($){
        $(window).load(function(){
			$(".scroll_pan").css({"display":"none !important"});
			$(".heading2").click();
        });
		
		$(".heading2").css({"cursor":"pointer"});
		$(".scroll_pan").mCustomScrollbar();
		$(".heading2").click(function(){
			if($(".scroll_pan").is(':visible')){
				$(this).next(".scroll_pan").slideUp();
				
				$(this).find("span").removeClass("dwn_arw");
				$(this).find("span").addClass("up_arw");
				
			}else{
			    $(this).next(".scroll_pan").slideDown();
				$(this).find("span").removeClass("up_arw");
				$(this).find("span").addClass("dwn_arw");
				
				
				
			}
		});
		
		
    })(jQuery);