$(document).ready(function() {
	// Color the scale
	resetScale();
	
	/*------Rating the claim-------*/
	// Add or update user's rating on this claim
	$('input[name=claimscore]').click(function() {
		$.ajax({
			type: 'POST',
			url: '/action/sendRating',
			data: {
				rating: $(this).attr('value'),
				claimID: $(this).attr('ccID')
			},
			dataType: 'json',
			success: function(json) {
				alert('Thanks for rating this claim! Go ahead and add a comment below!');
				window.location.reload();
			},
			error: function() {
				alert('Oops, are you logged in?');
			}
		});
	});


	/*------Voting On Comments-------*/

	$.each($('.buttonsContainer'), function() {
		if ($(this).find('.upVote').attr('voted') == '1' || $(this).find('.downVote').attr('voted') == '1') {
			$(this).addClass('beenVoted');
		}
	});

	//Onclick, send vote to server
	//switch value when clicked
	$('.upVote, .downVote').click(function() {
		$(this).parent().find('.buttons').removeClass('selectedVote');
		sendCommentVote($(this));
	});

	function sendCommentVote(button) {
		var voted = parseInt($(button).attr('voted')); //0 if not voted, 1 if voted
		var clicked = $(button);
		var value = $(button).attr('value');
		var oldUpVotes = parseInt($(clicked.parent().find(".upNum")).text());
		var oldDownVotes = parseInt($(clicked.parent().find(".downNum")).text());
		if(!voted)  {		
			if (clicked.hasClass('upVote')) {
				oldUpVotes++;
				if (clicked.parent().hasClass('beenVoted')) {
					oldDownVotes--;					
				}
			} else if (clicked.hasClass('downVote')) {
				oldDownVotes++;
				if (clicked.parent().hasClass('beenVoted')) {
					oldUpVotes--;
				}
			}
			clicked.parent().addClass('beenVoted');
			clicked.parent().find('.buttons').attr('voted','0');
			clicked.attr('voted','1');
			clicked.addClass('selectedVote');
		} else if (voted) {
			if (clicked.hasClass('upVote')) {
				oldUpVotes--;
			} else if (clicked.hasClass('downVote')) {
				oldDownVotes--;
			}
			clicked.parent().removeClass('beenVoted');
			clicked.attr('voted','0');
			clicked.removeClass('selectedVote');
		}
		$(clicked.parent().find(".upNum")).text(oldUpVotes);
		$(clicked.parent().find(".downNum")).text(oldDownVotes);
		$.ajax({
			type: 'POST',
			url: '/action/voteComment',
			data: {
				ClaimID: $('#discussionContainer').attr('claimid'),
				CommentID: parseInt($(clicked).attr('for')),
				voted: voted,
				value: value
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
	};


	/*------------Flagging stuff-----------------------*/
	$('#flagButton').tooltipster({
		trigger: 'click',
		interactive: true,
		interactiveTolerance: 5000,
		position: 'bottom',
		functionReady: function(origin, tooltip) {
			$('#flagNoncredible').click(function() {
				flagContent($(this), 'claim', 'noncredible');
			});

			$('#flagWrong').click(function() {
				flagContent($(this), 'claim', 'wrongcompany');
			});
		}
	});

	$('img.flagComment').click(function() {
		flagContent($(this), 'comment', 'badcomment');
	});

	function flagContent (button, targettype, flagetype) {
		var clicked = $(button);
		var targetID;

		if (targettype == 'claim') {
			targetID = $('#discussionContainer').attr('claimid');
		} else {
			targetID = $(clicked).attr('commentID');
		};
		
		var targetType = targettype;
		var flagType = flagetype;
	
		$.ajax({
			type: 'POST',
			url: 'http://127.0.0.1/action/flagContent',
			data: {
				targetID: targetID,
				targetType: targetType,
				flagType: flagType
				// industryID: $(this).attr('tagid'),
				// companyID: $(this).attr('companyid'),
				// voted: voted
			},
			dataType: 'json',
			success: function(json) {
				//Dom changes processed pre-query
				alert('Flagged');
			},
			error: function(json) {
				//alert error message for now
				alert(json.responseJSON.message);
			}
		});
	}



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
			url: '/action/upvoteTag',
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

	/*-----------------Discussion-----------------------*/

	// Injects a new textbox to start a thread
	$('#newComment').click(function() {
		// $('#newCommentPopup').show(200);
		// $('#newCommentPopup textarea').focus();
		// $('.lightsout').fadeIn();
		$('#newCommentBox').show(200);
		$('#newCommentBox textarea').focus();
	});

	// Injects a new textbox to reply to the above comment
	$('.reply').click(function() {
		$parentLi = $(this).parent().parent().attr('id');
		$('#' + $parentLi + 'reply').show();
		$('#' + $parentLi + 'reply textarea').focus();
	});

	// Submit a new thread or reply to database
	$('.submitReply').click(function() {
		if ($(this).attr('id') == 'newThread') {
			$parentCommentID = 0;
			$level = 0;
		} else {
			$parentCommentID = parseInt($(this).parent().attr('id'));
			$level = parseInt($('#' + $parentCommentID + 'comment').attr('level')) + 1;
		}
		$.ajax({
			type: 'POST',
			url: '/action/addComment',
			data: {
				claimID: $('#discussionContainer').attr('claimID'),
				comment: ($(this).parent().find('textarea')).val(),
				parentCommentID: $parentCommentID,
				level: $level
			},
			dataType: 'json',
			success: function(json) {
				window.location.reload();
				//Dom changes processed pre-query
			},
			error: function(json) {
				//alert error message for now
				alert('Oops, are you logged in?');
			}
		});
	});

	//Cancel reply
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
		// TODO: collase children of this
		console.log('collapse all children of this');
	});
});
