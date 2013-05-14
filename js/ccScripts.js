var colors = ['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#61D7A4', '#36D792', '#00AF64'];

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
	
	var avg = parseFloat($('#averageScore').text());
	if (avg < -2) {
		insertColor(colors[0]);
	} else if (avg < -1) {
		insertColor(colors[1]);
	} else if (avg < 0) {
		insertColor(colors[2]);
	} else if (avg == 0) {
		insertColor(colors[3]);
	} else if (avg < 1) {
		insertColor(colors[4]);
	} else if (avg < 2) {
		insertColor(colors[5]);
	} else if (avg > 2) {
		insertColor(colors[6]);
	}
	
	$.each($('.claimScore'), function() {
		var thisVal = parseFloat($(this).text());
		if (thisVal < -2) {
			$(this).parent().css('background-color', colors[0]);
		} else if (thisVal < -1) {
			$(this).parent().css('background-color', colors[1]);
		} else if (thisVal < 0) {
			$(this).parent().css('background-color', colors[2]);
		} else if (thisVal == 0) {
			$(this).parent().css('background-color', colors[3]);
		} else if (thisVal < 1) {
			$(this).parent().css('background-color', colors[4]);
		} else if (thisVal < 2) {
			$(this).parent().css('background-color', colors[5]);
		} else if (thisVal > 2) {
			$(this).parent().css('background-color', colors[6]);
		}
	});
});

function insertColor(color) {
		$('#averageScore').css('color', color);
		$('#scoreContent').css('border-left', '5px solid ' + color);
		$('#claimPopTags li').css('background-color', color);
}

function resetScale() {
	$.each($('.scoreBox'), function(i) {
		$(this).css('background-color', colors[i]);
		$(this).css('border', '2px solid ' + colors[i]);
		$(this).removeClass('selectedRating');
	});
}