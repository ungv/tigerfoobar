$(document).ready(function() {
	/*-----Coloring-------*/
	//Method found in general.js
	resetScale();

	applyColors(parseFloat($('#averageScore').text()), $('#averageScore'), 'color');
	applyColors(parseFloat($('#averageScore').text()), $('#scoreContent'), 'border-left', '5px solid ');
	applyColors(parseFloat($('#averageScore').text()), $('#claimPopTags li'), 'background-color');
	
	$.each($('.claimScore'), function() {
		applyColors(parseFloat($(this).text()), $(this).parent(), 'background-color');
	});

	$.each($('#discussionContent li'), function() {
		applyColors(parseInt($(this).attr('value')), $(this), 'border-left', '5px solid ');
	});
	
	/*------Rating the claim-------*/
	// Add or update user's rating on this claim
	$('input[name=claimscore]').click(function() {
		if (isLoggedIn('to rate this claim')) {
			$.ajax({
				type: 'POST',
				url: '/~bc28/action/sendRating',
				data: {
					rating: $(this).attr('value'),
					claimID: $(this).attr('claimid')
				},
				dataType: 'json',
				success: function(json) {
					alert('Thanks for rating this claim! Go ahead and add a comment below!');
					window.location.reload();
				},
				error: function() {
					console.log('error in rating the claim');
				}
			});
		}
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
		if (isLoggedIn('to vote on this comment')) {
			$(this).parent().find('.buttons').removeClass('selectedVote');		//Reset selection loaded from server
			sendCommentVote($(this));
		}
	});

	//Send vote to server
	function sendCommentVote(button) {
		var voted = parseInt($(button).attr('voted')); //0 if not voted, 1 if voted
		var clicked = $(button);
		var value = $(button).attr('value');
		//Update vount counts to reflect changes
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
		$.ajax({
			type: 'POST',
			url: '/~bc28/action/voteComment',
			data: {
				ClaimID: $('#discussionContainer').attr('claimid'),
				CommentID: parseInt($(clicked).attr('for')),
				voted: voted,
				value: value
			},
			dataType: 'json',
			success: function(json) {
			},
			error: function(json) {
				//Must be logged in to cast vote
				$('#loginPopup').show(200);
				window.scrollTo(0, 0);
			}
		});
	}

	/*--------Flags------------*/
	$('#flagButton').tooltipster({
		trigger: 'click',
		interactive: true,
		interactiveTolerance: 5000,
		position: 'bottom',
		functionReady: function(origin, tooltip) {
			$('#flagNoncredible').click(function() {
				if (isLoggedIn('to flag this non-credible evidence')) {
					flagContent($(this), 'claim', 'noncredible');
				}
			});

			$('#flagWrong').click(function() {
				if (isLoggedIn('to flag the company linking')) {
					flagContent($(this), 'claim', 'wrongcompany');
				}
			});
		}
	});

	$('label[for="radio3"]').click(function() {
		if ($('#radio3').attr('name') == 'claimscore') {
			if (isLoggedIn('to flag the company linking')) {
				flagContent($(this), 'claim', 'trivial');
			}
		}
	});

	$('img.flagComment').click(function() {
		if (isLoggedIn('to flag this comment')) {
			flagContent($(this), 'comment', 'badcomment');
		}
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
			url: '/~bc28/action/flagContent',
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
		if (isLoggedIn('to vote on this tag')) {
			sendTagUpvote($(this));
		}
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
			clicked.attr('voted','1');
			oldVotes ++;
			clicked.parent().removeClass('notVoted').addClass('userVoted');
		}else {				//just unvoted, remove vote
			clicked.attr('voted','0');
			oldVotes --;
			clicked.parent().removeClass('userVoted').addClass('notVoted');
		}
		$(clicked.parent().children(".tagTotal")[0]).text(oldVotes);
		$.ajax({
			type: 'POST',
			url: '/~bc28/action/upvoteTag',
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
		$('#newTagPopup').show();
		$('#addTag').hide();
		$('#newtag_name').focus();
	});

	//When the newtag text input loses focus,
	//hide it and show the add tag button again
	$('#newtag_name').blur(function() {
		$('#addTag').show();
		$('#newTagPopup').hide();
		$('#newtag_name').val("");
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
	                        'id':  value.id ,
	                        'name': value.name
	                    };  
	                }));
	            }   
	        }); 
	    },
		open: function() { 
			$('.ui-menu').width(270); 
			//$(".ui-menu-item").hide();
			//$(".ui-autocomplete").hide();
			//$("#autoCompleteResults").show();
		},
	    select: function( event, ui ) {
	    	//send tag upvote to server
	    	if(ui.item.id == -1) {		//totally new tag, create then upvote
	    		var newTagName = $("#newtag_name").val();
	    		createTag(newTagName);
	    	}else {
				voteOnNewTag(ui.item.id,ui.item.name);
			}
			$(this).val(''); 
			return false;
		}
	}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		//check if header
		var autocompleteLi;
		if(item.name == "Possible Tags") {
			autocompleteLi = $( "<li>" , {
				"class": "autoCompleteHeader",
				"text" : item.name
			});
		}else if(item.name == "empty") {
			autocompleteLi = $( "<li>" , {
				"html" : 	'<a href="#">Click to add <span class="originalTagPreview">' + 
								$("#newtag_name").val() + 
							'</span> as new tag</a>'
			});
		}else {
			autocompleteLi = $( "<li>" , {
				"class": "tagSuggestion",
				"tagid": item.id,
				"text" : item.name
			}).html('<a href="#">'+item.name+'</a>');
		}
		autocompleteLi.appendTo(ul);
		return autocompleteLi;
	};;

	function createTag(name) {
		var newType;
		if($('#taglist').attr('tagtype') == "Industry") {
			newType = "industry";
		}else {
			newType = "claimTag";
		}
		var newID;
		$.ajax({
			type: 'POST',
			url: '/~bc28/action/createTag/'+name+"/"+newType,
			success: function(r) {	//Return saying tag was created
				if(parseInt(r) != -1) {
					voteOnNewTag(parseInt(r),name);
				}
			},
			error: function(r) {
				//
			}
		});
	}
	
	//Sends information about the newly voted on
	//industry-company connection to the server
	//if already a current tag, tries to upvote that instead
	function voteOnNewTag(id, name) {
		//check if already there
		var tagCheck = ($("#taglist").find("[tagid='" + id + "']"));

		if(tagCheck.length > 0) { //tag has been created, upvote if can
			//check if user has voted on it
			if(tagCheck.attr("voted") == "0") {//if user has voted, do nothing
				tagCheck.click();
			}
		}else {
			var tagtype = $('#taglist').attr('tagtype');
			var newLink = $('<li>', {
				"html": '	<div class="fancyTags notVoted">' +
						'	<span class="tagName"><a href="/tag/'+id+'">' + name + '</a></span>' +
						'	<span>(</span> ' +
						'		<span class="tagTotal">0</span>' +
						'	<span>)</span>' +
						'	<span class="tagUpvote" tagtype="' + tagtype + '" tagid="'+ id + '" objectid="'+ $('#taglist').attr('objectid') +'" voted="0">' +
						'	&nbsp;' +
						'	</span>' +
						'	</div>'
			});
			$('#taglist').append(newLink);
			$("#newtag_name").val(""); //clear textbox
			//call current vote method, triger click
			$(newLink.find('.tagUpvote')[0]).click(function() {
				sendTagUpvote($(this));
			});
			$(newLink.find('.tagUpvote')[0]).click();
		}
		//pulse green at end
	}


	/*-----------------Discussion-----------------------*/

	// Injects a new textbox to start a thread
	$('#newComment').click(function() {
		// $('#newCommentPopup').show(200);
		// $('#newCommentPopup textarea').focus();
		// $('.lightsout').fadeIn();
		if (isLoggedIn('add a new comment')) {
			$('#newCommentBox').show(200);
			$('#newCommentBox textarea').focus();
		}
	});

	// Injects a new textbox to reply to the above comment
	$('.reply').click(function() {
		if (isLoggedIn('reply to this comment')) {
			$parentLi = $(this).parent().parent().attr('id');
			$('#' + $parentLi + 'reply').show();
			$('#' + $parentLi + 'reply textarea').focus();
		}
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
			url: '/~bc28/action/addComment',
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


// tooltips
$('label.scoreBox, span.tagUpvote, a.addTag').tooltipster();
$('span.tagUpvote').tooltipster();
$('#addTag').tooltipster();
$('img.flagComment').tooltipster();
$('li.comments').tooltipster({
	position: 'left'
});
