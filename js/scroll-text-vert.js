(function($) {	 
  $.fn.marquee = function(speed) {

  	$(this).css({
      'margin'     : '0',
      'padding'    : '0',
  		'position'	 : 'relative',
  		'height'		 : '400',
  		'text-align' : 'center',
      'list-style' : 'none',
      'display'    : 'block',
      'overflow'   : 'hidden'
  	});
  	
  	$('li', this).css({
  		'position' : 'absolute',
  		'width' 	 : '100%'
  	});

    var height = parseInt($('.marquee li').css('height'), 10),
        num_of_li = $('.marquee li').size(),
        marq_height = parseInt($('.marquee').css('height'), 10),
        hover = false;

  	$('.marquee li').each(function() {

			var thisIndex = $('li').index($(this)) + 1,
					thisPosTop = (thisIndex*height) - ((num_of_li * height) - (marq_height+height));

			$(this).css({'top': thisPosTop });

  	});

  	$(this).mouseover(function() {
  		
  		hover = true;

  	}).mouseout(function() {

			hover = false;
			go();

  	});

    function go() {
    	if (!hover) {

	  		$('.marquee li').each(function() {

	  			$(this).css({'top':'+=1'})

	  			if(parseInt($(this).css('top'), 10) >= (num_of_li*height) - height) {
	  				$(this).css({'top': -height});
	  			}

	  		});
	
	      setTimeout(go, speed);

    	}
    };

    go();
  };
  
	$(".marquee").marquee(44);
})(jQuery);