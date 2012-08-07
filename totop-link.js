jQuery(function() {
	jQuery(window).scroll(function() {
		var soffset = jQuery('#toTop').attr('rel');
		soffset = (soffset) ? soffset : 0;
		if(jQuery(this).scrollTop() > soffset) {
			jQuery('#toTop').fadeIn();	
		} else {
			jQuery('#toTop').fadeOut();
		}
	});
 
	jQuery('#toTop').click(function() {
		jQuery('body,html').animate({scrollTop:0},800);
	});	
});