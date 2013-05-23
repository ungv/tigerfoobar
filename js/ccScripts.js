

$(document).ready(function() {
	resetScale();
	

	/*------Upvoting Industry Tags-------*/


	//Onclick, send vote to server
	//switch value when clicked
	$('.tagUpvote').click(function() {
		sendIndustryUpvote($(this));
	});

	function sendIndustryUpvote(button) {
		var voted = parseInt($(button).attr('voted')); //0 if not voted, 1 if voted
		//alert($(this).attr('tagid'));
		var clicked = $(button);
		//Dom changes
		var oldVotes = parseInt($(clicked.parent().children(".tagTotal")[0]).text());
		if(!voted) {		//just voted, add vote
			clicked.text('-');
			clicked.attr('voted','1');
			oldVotes ++;
			clicked.parent().addClass('userVoted');
		}else {				//just unvoted, remove vote
			clicked.text('+');
			clicked.attr('voted','0');
			oldVotes --;
			clicked.parent().removeClass('userVoted');
		}
		$(clicked.parent().children(".tagTotal")[0]).text(oldVotes);
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
	$('#addTag').click( function() {
		$('#newTagPopup').show(200);
		$('#newclaimtag_name').focus();
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

	//Method found in general.js
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

	/*-----------------Discussion-----------------------*/

	$('#newComment').click(function() {
		// $('#newCommentPopup').show(200);
		// $('#newCommentPopup textarea').focus();
		// $('.lightsout').fadeIn();
		$('#newCommentBox').show(200);
		$('#newCommentBox textarea').focus();
	});
	
	$('.reply').click(function() {
		$parentLi = $(this).parent().parent().attr('id');
		$('#' + $parentLi + 'reply').show();
		$('#' + $parentLi + 'reply textarea').focus();
	});

	$('.submitReply').click(function() {
		
	});

	$('.cancelButton').click(function() {
		// $('.lightsout').fadeOut();
		$('.replyBox').hide();		
		$('#newCommentBox').hide(200);
	});

	$('.buttonsContainer').hover(function() {
		$(this).css('opacity', '1');
	}, function() {
		$(this).css('opacity', '0.4');
	});

	$('#discussionContent li').click(function() {
		console.log('collapse all children of this');
	});
});
