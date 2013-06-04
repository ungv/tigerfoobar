$(document).ready(function() {
	$.each($('#claimsList li'), function() {
		applyColors(parseFloat($(this).attr('id')), $(this), 'background-color');
	});
});