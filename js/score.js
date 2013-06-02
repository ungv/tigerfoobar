$(document).ready(function() {
	// Color the scale
	resetScale();

	//Alert Kudos value on hover
	$('.scoreBox').hover(
		function() {
			$(this).text($(this).attr('value'));
		},
		function() {
			$(this).text('');
		}
	);
	
	// Show which box was selected by this user
	$.each($('.scoreBox'), function() {
		if ($(this).attr('hasratedthis') == 1) {
			$(this).addClass('selectedRating');
		}
	});

	// Clear out all other selections and show clicked box as selected
	$('input[name=claimscore]').click(function() {
		resetScale();
		$(this).addClass('selectedRating');
	});
});