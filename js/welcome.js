$(document).ready(function() {
	/*------------Welcome message stuff-----------------*/
	if ($('#main').attr('signedin') == 0)
		$('.lightsout').fadeIn(400);
	$(window).click(function() {
		$('.lightsout').fadeOut(500);
		$('#welcomeMessage').fadeOut(500);
	});
});