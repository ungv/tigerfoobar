$(document).ready(function() {
	resetScale();
	
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
	
	$('.scoreBox').click(function() {
		resetScale();
		$(this).addClass('selectedRating');
	});

	$('#newComment').click(function() {
		$('#newCommentPopup').show(200);
	});

	$('.cancelButton').click(function() {
		$('#newCommentPopup').hide(200);
	});

	$('.submitButton').click(function() {
	});
	
	applyStyles(parseFloat($('#averageScore').text()), $('#averageScore'), 'color');
	applyStyles(parseFloat($('#averageScore').text()), $('#scoreContent'), 'border-left', '5px solid ');
	applyStyles(parseFloat($('#averageScore').text()), $('#claimPopTags li'), 'background-color');
	
	$.each($('.claimScore'), function() {
		applyStyles(parseFloat($(this).text()), $(this).parent(), 'background-color');
	});

	$.each($('#discussionContent li'), function() {
		applyStyles(parseInt($(this).attr('value')), $(this), 'border-left', '5px solid ');
	});
});

function resetScale() {
	$.each($('.scoreBox'), function(i) {
		$(this).css('background-color', colors[i]);
		$(this).css('border', '2px solid ' + colors[i]);
		$(this).removeClass('selectedRating');
	});
}

function applyStyles(thisVal, $element, styling, stylewith) {
	if (thisVal < -2) {
		$element.css(styling, stylewith + colors[0]);
	} else if (thisVal < -1) {
		$element.css(styling, stylewith + colors[1]);
	} else if (thisVal < 0) {
		$element.css(styling, stylewith + colors[2]);
	} else if (thisVal == 0) {
		$element.css(styling, stylewith + colors[3]);
	} else if (thisVal < 1) {
		$element.css(styling, stylewith + colors[4]);
	} else if (thisVal < 2) {
		$element.css(styling, stylewith + colors[5]);
	} else if (thisVal > 2) {
		$element.css(styling, stylewith + colors[6]);
	}
}