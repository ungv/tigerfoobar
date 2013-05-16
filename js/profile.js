$(document).ready(function() {
	$.each($('#submissions li'), function() {
		var thisVal = parseFloat($(this).attr('id'));
		if (thisVal < -2) {
			$(this).css('background-color', colors[0]);
		} else if (thisVal < -1) {
			$(this).css('background-color', colors[1]);
		} else if (thisVal < 0) {
			$(this).css('background-color', colors[2]);
		} else if (thisVal == 0) {
			$(this).css('background-color', colors[3]);
		} else if (thisVal < 1) {
			$(this).css('background-color', colors[4]);
		} else if (thisVal < 2) {
			$(this).css('background-color', colors[5]);
		} else if (thisVal > 2) {
			$(this).css('background-color', colors[6]);
		}
	});
	
	$.each($('.theirRating'), function() {
		var thisVal = parseFloat($(this).text());
		if (thisVal < -2) {
			$(this).css('color', colors[0]);
		} else if (thisVal < -1) {
			$(this).css('color', colors[1]);
		} else if (thisVal < 0) {
			$(this).css('color', colors[2]);
		} else if (thisVal == 0) {
			$(this).css('color', colors[3]);
		} else if (thisVal < 1) {
			$(this).css('color', colors[4]);
		} else if (thisVal < 2) {
			$(this).css('color', colors[5]);
		} else if (thisVal > 2) {
			$(this).css('color', colors[6]);
		}
	});
	
	$.each($('.liked'), function() {
		$(this).css('color', colors[6]);
	});
	$.each($('.disliked'), function() {
		$(this).css('color', colors[0]);
	});
});