$(document).ready(function() {
	// Color the scale
	resetScale();	

	//Alert Kudos value on hover
	$('.scoreBox').hover(
		function() {
			if ($(this).attr('value') == 0)
				$(this).text('F');
			else
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
	$('.scoreBox').click(function() {
		resetScale();
		$(this).addClass('selectedRating');
	});

	$.each($('.claimScore'), function() {
		applyColors(parseFloat($(this).text()), $(this).parent(), 'background-color');
	});

	$.each($('#discussionContent li'), function() {
		applyColors(parseInt($(this).attr('value')), $(this), 'border-left', '5px solid ');
	});
});