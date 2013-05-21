

$(document).ready(function() {
	resetScale();
	

	/*------Upvoting Industry Tags-------*/


	//Onclick, send vote to server
	//switch value when clicked
	$('.industryUpvote').click(function() {
		sendIndustryUpvote($(this));
	});

	function sendIndustryUpvote(button) {
		var voted = parseInt($(button).attr('voted')); //0 if not voted, 1 if voted
		//alert($(this).attr('tagid'));
		var clicked = $(button);
		//Dom changes
		var oldVotes = parseInt($(clicked.parent().children(".industryTotal")[0]).text());
		if(!voted) {		//just voted, add vote
			clicked.text('-');
			clicked.attr('voted','1');
			oldVotes ++;
			clicked.parent().addClass('industryUserVoted');
		}else {				//just unvoted, remove vote
			clicked.text('+');
			clicked.attr('voted','0');
			oldVotes --;
			clicked.parent().removeClass('industryUserVoted');
		}
		$(clicked.parent().children(".industryTotal")[0]).text(oldVotes);
		$.ajax({
			type: 'POST',
			url: 'http://127.0.0.1/action/upvoteIndustry',
			data: {
				industryID: $(clicked).attr('tagid'),
				companyID: $(clicked).attr('companyid'),
				voted: voted
			},
			dataType: 'json',
			success: function(json) {
				//Dom changes processed pre-query
			},
			error: function(json) {
				//alert error message for now
				alert('Server Error');
			}
		});
	}

	//Displays textbox to add new industry tag
	$('#addIndustry').click( function() {
		$('#newIndustryPopup').show(200);
	});

	//Autocomplete for adding new industry
	var projects = [
		{
			value: "jquery",
			label: "jQuery",
			desc: "the write less, do more, JavaScript library",
			icon: "jquery_32x32.png"
		}
	];

	//Called when typing into new industry text box
	$( "#newindustry_name" ).autocomplete({
		minLength: 0,
		source: function (request, response) {
	        $.ajax({
	            url: "/data/industryList/" + $('#newindustry_name').val(),
	            dataType: 'json',
	            success: function (data) {
	                response(data.map(function (value) {
	                    return {
	                        'label':  value.value ,
	                        'value': value.label
	                    };  
	                }));
	            }   
	        }); 
	    }, 
	    select: function( event, ui ) {
	    	/*
			alert( ui.item ?
			"Selected: " + ui.item.value :
			"Nothing selected, input was " + this.value );
			*/
			sendNewIndustry(ui.item.label,ui.item.value);
		}
	});
	
	//Sends information about the newly created
	//industry-company connection to the server
	function sendNewIndustry(label, value) {
		var newLink = $('<li>', {
			"html": '	<span class="industryName">' + label + '</span>' +
					'	<span>(</span> ' +
					'		<span class="industryTotal">0</span>' +
					'	<span>)</span>' +
					'	<span class="industryUpvote" tagid="'+ value + '" companyid="'+ $('#industryTags').attr('companyid') +'" voted="0">' +
					'		+  ' +
					'	</span>'
		});
		$('#industryTags').append(newLink);
		//call current vote method, triger click
		$(newLink.children('.industryUpvote')[0]).click(function() {
			sendIndustryUpvote($(this));
		});
		$(newLink.children('.industryUpvote')[0]).click();
	}


	/*-----------------Kudos Scale-----------------------*/


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

	applyColors(parseFloat($('#averageScore').text()), $('#averageScore'), 'color');
	applyColors(parseFloat($('#averageScore').text()), $('#scoreContent'), 'border-left', '5px solid ');
	applyColors(parseFloat($('#averageScore').text()), $('#claimPopTags li'), 'background-color');
	
	$.each($('.claimScore'), function() {
		applyColors(parseFloat($(this).text()), $(this).parent(), 'background-color');
	});

	$.each($('#discussionContent li'), function() {
		applyColors(parseInt($(this).attr('value')), $(this), 'border-left', '5px solid ');
	});

	//Resets and recolors the kudos scale to get rid of border color
	function resetScale() {
		$.each($('.scoreBox'), function(i) {
			$(this).css('background-color', colors[i]);
			$(this).css('border', '2px solid ' + colors[i]);
			$(this).removeClass('selectedRating');
		});
	}

	//Apply css coloring defined in global COLORS variable to element based on the element's value
	function applyColors(thisVal, $element, styling, stylewith) {
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

	/*-----------------Discussion-----------------------*/

	$('#newComment').click(function() {
		$('#newCommentPopup').show(200);
		$('.lightsout').fadeIn();
	});

	$('.cancelButton').click(function() {
		$('#newCommentPopup').hide(200);
		$('.lightsout').fadeOut();
	});

	$('.reply').click(function() {
		$parentLi = $(this).parent().parent().attr('id');
		$('#' + $parentLi + 'reply').show();
	});

	$('.submitReply').click(function() {
		
	});

	$('.cancelReply').click(function() {
		$('.replyBox').hide();		
	});

	$('.buttonsContainer').hover(function() {
		$(this).css('opacity', '1');
	}, function() {
		$(this).css('opacity', '0.4');
	});

	$('#discussionContent li').click(function() {
		console.log($(this).attr('level'));
	});
});
