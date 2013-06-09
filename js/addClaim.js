$(document).ready(function() {
	$('#newClaimForm').validate({
		rules: {
			pasteURL: {
				required: true,
				url: true
			},
			title: {
				required: true
			},
			assocCo: {
				required: true
			}
		},
		messages: {
			pasteURL: {
				required: "^Your claim should be supported with evidence"
			},
			title: {
				required: "^We'd very much like a title"
			},
			assocCo: {
				required: "^Whom does this article mostly refer to?"
			}
		}
	});

	$('#newClaimForm input, textarea').focus(function() {
		isLoggedIn('add a claim');
	});
	
	$('#pasteURL').blur(function() {
		$url = $('#pasteURL').val().split('#')[0];
		$.ajax({
			type: 'POST',
			url: '/action/urlFound',
			data: {
				url: $url
			},
			dataType: 'json',
			success: function(json) {
				// alert user that this url has already been submitted
				$('#urlCheck').html('This url has already been submitted <a href="/claim/' + json.claimID + '">here</a>').show();
				$('button').attr('disabled', 'disabled');
			},
			error: function() {
				// let user continue adding this claim
				console.log('no url found');
			}
		});

	});

	//------------this stuff is for dropping overlay to add claim-------------
	//Click on button with text to convert to input
	// $('#urlButton').click(function() {
	// 	if (isLoggedIn('add a claim')) {
	// 		$('#urlButton').hide('fade', 200);
	// 		$('#urlInput').show('fade', 600);
	// 		$('#pasteURL').focus();
	// 	}
	// });
	
	//Listen for paste event to popup add new claim form
	// $("#pasteURL").bind('paste', function() {
	// 	$('#urlInput').removeClass('quarter').addClass('threeQuarters');
	// 	$('#urlSubmit').show(200);
	// 	$('.lightsout').fadeIn();
	// });

	// Hide form and revert back to page refresh state
	// $(".cancelButton").click(function() {
	// 	$('#urlInput').removeClass('threeQuarters').addClass('quarter');
	// 	$('.popup').hide(200);
	// 	$("#pasteURL").val('');
	// 	$('#urlButton').show('fade', 600);
	// 	$('#urlInput').hide('fade', 200);
	// 	$('.lightsout').fadeOut();
	// 	return false;
	// });
});


// Send request to add new claim to database on submit click
// Form #newClaimForm calls this function on form submit
function addClaim() {
	if (isLoggedIn('add a claim')) {
		$url = $('#pasteURL').val().split('#')[0];
		$title = $('input[name=title]').val();
		$desc = $('textarea').val();
		$company = $('#assocCo').tagit('tags');
		$rating = $('input[name=score]:checked').val();
		if ($rating == null || $rating == -3 || $rating == -2 || $rating == -1 || $rating == 1 || $rating == 2 || $rating == 3) {
		} else {
			alert("Hey don't mess with us!");
			window.location.reload(true);
			return;
		}
		$tags = $('#tagsSearch').tagit('tags');

		// For some reason, ajax only allows plain objects
		var tagsObj = new Object();
		for (var i in $tags) {
			tagsObj[i] = $tags[i].value;
		}
		if ($url != '' && $title != '' && $company != '' && $rating != null) {
			$('#addClaim').text('').addClass('loadingGif').attr('disabled', 'disabled');
			// animate pacman moving across screen
			// $('#loadingGif').show().animate(function() {
			// 	left: '200px'
			// }, 10000);
			$.ajax({
				type: 'POST',
				url: '/action/addClaim',
				data: {
					url: $url,
					title: $title,
					desc: $desc,
					company: $company[0].value,
					rating: $rating,
					tags: tagsObj
				},
				dataType: 'json',
				success: function(json) {
					$('.alertMessage').text('Your claim has been submitted!');
					$('.alertMessage').fadeIn();
					$('.alertMessage').fadeOut(3000);
					if (json.message == "Successfully contacted server method!") {
						console.log('success');
						window.location = "/claim/" + json.claimid;
					}
				},
				error: function() {
					alert('Oops, are you logged in?');
				}
			});
		} else {
			$('#coNote, #ratingNote').css('color', 'red');
			if ($url == '')
				$('#urlNote').show(200);
			if ($company == '')
				$('#coNote').show(200);
			if ($rating == null)
				$('#ratingNote').show(200);
		}
	}
}