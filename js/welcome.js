$(document).ready(function() {
	/*------------Welcome message stuff-----------------*/
	$('#welcomeContainer').css('height', $('body').height());
	$('#welcomeContainer').fadeIn(500);
	$(window).click(function() {
		$('#welcomeContainer').fadeOut(500);
	});
});