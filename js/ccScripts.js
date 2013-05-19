

$(document).ready(function() {
	resetScale();
	

	/*------Upvoting Industry Tags-------*/


	//Onclick, send vote to server
	//switch value when clicked
	$('.industryUpvote').click(function() {
		var voted = parseInt($(this).attr('voted')); //0 if not voted, 1 if voted
		//alert($(this).attr('tagid'));
		var clicked = $(this);
		$.ajax({
			type: 'POST',
			url: 'http://127.0.0.1/action/upvoteIndustry',
			data: {
				industryID: $(this).attr('tagid'),
				companyID: $(this).attr('companyid'),
				voted: voted
			},
			dataType: 'json',
			success: function(json) {
				var oldVotes = parseInt($(clicked.parent().children(".industryTotal")[0]).text());
				if(!voted) {		//just voted, add vote
					clicked.text('-');
					clicked.attr('voted','1');
					oldVotes ++;
				}else {				//just unvoted, remove vote
					clicked.text('+');
					clicked.attr('voted','0');
					oldVotes --;
				}
				$(clicked.parent().children(".industryTotal")[0]).text(oldVotes);
				//TODO: add class to parent "industryUpvoted" to show you voted
			},
			error: function(json) {
				//alert error message for now
				alert('Server Error');
			}
		});
	});


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
