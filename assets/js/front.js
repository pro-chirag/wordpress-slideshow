jQuery(document).ready(function($) {

	// Shortcode - frontend display
	$('.wp-slide-slideshow').slick({
		autoplay: true,
		autoplaySpeed: 1000,
		dots: false,
		prevArrow: false,
		nextArrow: false,
		infinite: true,
		speed: 500,
		fade: true,
		cssEase: 'linear',
	});
});