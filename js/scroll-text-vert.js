(function($) {	 
	$.fn.marquee = function(speed) {

		// REQUIRED CSS for marquee animation to function
		$(this).css({ "position" : "relative" });
		$('li', this).css({ "position" : "absolute", "width" : "100%" });


		var height = parseInt($('.marquee li').css('height'), 10),
				num_of_li = $('.marquee li').size(),
				marq_height = parseInt($('.marquee').css('height'), 10),
				hover = false;

		// SET EACH LI POSITION TOP based on that li number in list
		$('.marquee li').each(function() {

			var thisIndex = $('li').index($(this)) + 1,
					thisPosTop = (thisIndex*height) - ((num_of_li * height) - (marq_height+height));

			$(this).css({'top': thisPosTop });

		});

		// ON MOUSEOVER pause marquee animation
		$(this).mouseover(function() {
			
			hover = true;

		}).mouseout(function() {

			hover = false;
			go();

		});

		function go() {
			if (!hover) {

				$('.marquee li').each(function() {

					// INCREMENT by 1 and repeat based on setTimeout speed
					$(this).css({'top':'+=1'}) 


					// IF this li top is greater than all li height - li height
					// RESET this li position to start of list 
					if(parseInt($(this).css('top'), 10) >= (num_of_li*height) - height) {
						$(this).css({'top': -height});
					}

				});
	
				setTimeout(go, speed);

			}
		};

		go();
	};
	
	$(".marquee").marquee(54);

})(jQuery);
