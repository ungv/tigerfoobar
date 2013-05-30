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
			title: {
				required: "^We'd very much like a title"
			},
			assocCo: {
				required: "^Whom does this article mostly refer to?"
			}
		}
	});

	//Click on button with text to convert to input
	$('#urlButton').click(function() {
		if (isLoggedIn('add a claim')) {
			$('#urlButton').hide('fade', 200);
			$('#urlInput').show('fade', 600);
			$('#pasteURL').focus();
		}
	});
	
	//Listen for paste event to popup add new claim form
	$("#pasteURL").bind('paste', function() {
		$('#urlInput').removeClass('quarter').addClass('threeQuarters');
		$('#urlSubmit').show(200);
		$('.lightsout').fadeIn();
	});

	// Hide form and revert back to page refresh state
	$(".cancelButton").click(function() {
		$('#urlInput').removeClass('threeQuarters').addClass('quarter');
		$('#urlSubmit').hide(200);
		$("#pasteURL").val('');
		$('#urlButton').show('fade', 600);
		$('#urlInput').hide('fade', 200);
		$('.lightsout').fadeOut();
		return false;
	});
});

// Send request to add new claim to database on submit click
// Form #newClaimForm calls this function on form submit
function addClaim() {
	$url = $('#pasteURL').val();
	$title = $('input[name=title]').val();
	$desc = $('textarea').val();
	$company = $('#assocCo').tagit('tags')[0].value;
	$rating = $('input[name=score]:checked').val();
	$tags = $('#tagsSearch').tagit('tags');

	// For some reason, ajax only allows plain objects
	var tagsObj = new Object();
	for (var i in $tags) {
		tagsObj[i] = $tags[i].value;
	}
	if ($url != '' && $title != '' && $company != '' && $rating != null) {
		$.ajax({
			type: 'POST',
			url: '/action/addClaim',
			data: {
				url: $url,
				title: $title,
				desc: $desc,
				company: $company,
				rating: $rating,
				tags: tagsObj
			},
			dataType: 'json',
			success: function(json) {
				hidePopups();
				alert('Your claim has been submitted!');
				window.location.reload();
			},
			error: function() {
				alert('Oops, are you logged in?');
			}
		});
	} else {
		$('#coNote, #ratingNote').css('color', 'red');
		$('#coNote').show(200);
		$('#ratingNote').show(200);
	}
}