<?php header('Content-type: text/javascript');
$speed = (!empty($_GET['speed'])) ? $_GET['speed'] : 800;
?>

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
		jQuery('body,html').animate({scrollTop:0},<?php echo $speed; ?>);
	});	
});