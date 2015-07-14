        jQuery(document).ready(function ($) {
            var options = {
                $BulletNavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
                    $Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
                    $ChanceToShow: 2,                         //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $AutoCenter: 1,                           //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                    $SpacingX: 25                              //[Optional] Horizontal space between each item in pixel, default value is 0
                }
            };

            var jssor_slider1 = new $JssorSlider$("slider1_container", options);
            var jssor_slider2 = new $JssorSlider$("slider2_container", options);

            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizes
            function ScaleSlider() {
                var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
                if (parentWidth) {
                    var sliderWidth = parentWidth;

                    //keep the slider width no more than 800
                    sliderWidth = Math.min(sliderWidth, 800);

                    jssor_slider1.$ScaleWidth(sliderWidth);
                }
                else
                    window.setTimeout(ScaleSlider, 30);
            }
			function ScaleSlider2() {
                var parentWidth = jssor_slider2.$Elmt.parentNode.clientWidth;
                if (parentWidth) {
                    var sliderWidth = parentWidth;

                    //keep the slider width no more than 800
                    sliderWidth = Math.min(sliderWidth, 800);

                    jssor_slider2.$ScaleWidth(sliderWidth);
                }
                else
                    window.setTimeout(ScaleSlider, 30);
            }
            ScaleSlider2();

            $(window).bind("load", ScaleSlider);
            $(window).bind("resize", ScaleSlider);
            $(window).bind("orientationchange", ScaleSlider);
			
			
			$(window).bind("load", ScaleSlider2);
            $(window).bind("resize", ScaleSlider2);
            $(window).bind("orientationchange", ScaleSlider2);
            //responsive code end
			
			
			
			$(".prev_icon").click(function(){ 
				var numberOfSlide = $(this).parents(".common_slider").find(".jssorb18 div").length,
				    currentIndex = $(this).parents(".common_slider").find(".jssorb18 div.av").index() +1,
					prevIndex = currentIndex - 1;			
					if(currentIndex != 1){
						$(this).parents(".common_slider").find(".jssorb18 div:nth-child("+prevIndex+")").click();
					}else{
						//$(".jssorb18 div:nth-child("+numberOfSlide+")").click();
					}
				
			});
			
			$(".next_icon").click(function(){
				var numberOfSlide = $(this).parents(".common_slider").find(".jssorb18 div").length,
				    currentIndex = $(this).parents(".common_slider").find(".jssorb18 div.av").index() +1,
					nextIndex = currentIndex + 1;
					
					if(currentIndex < numberOfSlide){
						$(this).parents(".common_slider").find(".jssorb18 div:nth-child("+nextIndex+")").click();
					}else{
						//$(".jssorb18 div:nth-child(1)").click();
					}
				
			});
			

			
        });