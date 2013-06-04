$(document).ready(function() {
	/*------------Welcome message stuff-----------------*/
	$('.lightsout').fadeIn(400);
	$(window).click(function() {
		$('.lightsout').fadeOut(500);
		$('#welcomeMessage').fadeOut(500);
	});
});