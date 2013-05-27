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

	//Keep track of comments that has either up/down vote already submitted
	$.each($('.buttonsContainer'), function() {
		if ($(this).find('.upVote').attr('voted') == '1' || $(this).find('.downVote').attr('voted') == '1') {
			$(this).addClass('beenVoted');
		}
	});

	//Onclick, send vote to server
	$('.upVote, .downVote').click(function() {
		$(this).parent().find('.buttons').removeClass('selectedVote');		//Reset selection loaded from server
		sendCommentVote($(this));
	});

	//Send vote to server
	function sendCommentVote(button) {
		var voted = parseInt($(button).attr('voted')); //0 if not voted, 1 if voted
		var clicked = $(button);
		var value = $(button).attr('value');
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
			success: function(json) {	//Update vount counts to reflect changes
				//Get current vote count
				var oldUpVotes = parseInt($(clicked.parent().find(".upNum")).text());
				var oldDownVotes = parseInt($(clicked.parent().find(".downNum")).text());

				if(!voted)  {		//If clicked on non-selected arrow
					if (clicked.hasClass('upVote')) {		//Clicked on up arrow
						oldUpVotes++;
						if (clicked.parent().hasClass('beenVoted')) {		//If either arrow has already been selected
							oldDownVotes--;					
						}
					} else if (clicked.hasClass('downVote')) {		//Clicked on down arrow
						oldDownVotes++;
						if (clicked.parent().hasClass('beenVoted')) {		//If either arrow has already been selected
							oldUpVotes--;
						}
					}
					clicked.parent().addClass('beenVoted');		//This comment has been voted on

					//Update which arrow has been selected
					clicked.parent().find('.buttons').attr('voted','0');
					clicked.attr('voted','1');
					clicked.addClass('selectedVote');

				} else if (voted) {		//else user wants to unvote selection
					if (clicked.hasClass('upVote')) {		//Clicked on up arrow
						oldUpVotes--;
					} else if (clicked.hasClass('downVote')) {		//Clicked on down arrow
						oldDownVotes--;
					}
					//This comment now does not have any votes selected
					clicked.parent().removeClass('beenVoted');
					clicked.attr('voted','0');
					clicked.removeClass('selectedVote');
				}
				//Inject new vote counts
				$(clicked.parent().find(".upNum")).text(oldUpVotes);
				$(clicked.parent().find(".downNum")).text(oldDownVotes);
			},
			error: function(json) {
				//Must be logged in to cast vote
				alert('You must be logged in to vote');
				$('#loginPopup').show(200);
				window.scrollTo(0, 0);
			}
		});
	}


	/*------Upvoting Industry Tags-------*/

	//Onclick, send vote to server
	//switch value when clicked
	$('.tagUpvote').click(function() {
		sendTagUpvote($(this));
	});

	//Sends the upvote or downvote to the server using the type of tag
	//(industry or claim tag) and other specific tag information
	function sendTagUpvote(button) {
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
				objectID: $(clicked).attr('objectid'), //the objet (claim or company) being affected
				tagType: $(clicked).attr('tagtype'),
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

	//Called when typing into new industry/new tag text box
	//Feteches list of claim tags or industries from server
	$( "#newtag_name" ).autocomplete({
		minLength: 0,
		source: function (request, response) {
	        $.ajax({
	            url: "/data/tagList/" + $('#newtag_name').val(),
	            data: {tagtype: $("#newtag_name").attr('tagtype')},
	            dataType: 'json',
	            success: function (data) {
	            	if (data.length == 0) {
	            		data.push(
			          		{
		                        'label':  'No tags found.' ,
		                        'value':  -1
		                    }
		                );
		            }
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
			sendNewTag(ui.item.label,ui.item.value);
			$(this).val(''); 
			return false;
		}
	});
	
	//Sends information about the newly created
	//industry-company connection to the server
	function sendNewTag(label, value) {
		var tagtype = $('#taglist').attr('tagtype');
		var newLink = $('<li>', {
			"html": '	<span class="tagName">' + label + '</span>' +
					'	<span>(</span> ' +
					'		<span class="tagTotal">0</span>' +
					'	<span>)</span>' +
					'	<span class="tagUpvote" tagtype="'+tagtype+'" tagid="'+ value + '" objectid="'+ $('#taglist').attr('objectid') +'" voted="0">' +
					'		+  ' +
					'	</span>'
		});
		$('#taglist').append(newLink);
		$("#newtag_name").val(""); //clear textbox
		//call current vote method, triger click
		$(newLink.children('.tagUpvote')[0]).click(function() {
			sendTagUpvote($(this));
		});
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

	//Semi-hide controls until hover over
	$('.buttonsContainer').hover(function() {
		$(this).css('opacity', '1');
	}, function() {
		$(this).css('opacity', '0.4');
	});

	//Collapse all children of this
	$('#discussionContent li').click(function() {
		// TODO: collase children of this
		console.log('collapse all children of this');
	});
});
