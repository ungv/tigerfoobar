$(document).ready(function() {
	resetScale();
	
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
			url: 'http://127.0.0.1/action/voteComment',
			data: {
				ClaimID: $(clicked).attr('ClaimID'),
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
			url: 'http://127.0.0.1/action/upvoteTag',
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
		$(newLink.children('.tagUpvote')[0]).click();
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
