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
		$('#urlButton').hide('fade', 200);
		$('#urlInput').show('fade', 600);
		$('#pasteURL').focus();
	});
	
	//Listen for paste event to popup add new claim form
	$("#pasteURL").bind('paste', function() {
		$('#urlInput').removeClass('quarter').addClass('full');
		$('#urlSubmit').show(200);
		$('.lightsout').fadeIn();
	});

	// Hide form and revert back to page refresh state
	$(".cancelButton").click(function() {
		$('#urlInput').removeClass('full').addClass('quarter');
		$('#urlSubmit').hide(200);
		$("#pasteURL").val('');
		$('#urlButton').show('fade', 600);
		$('#urlInput').hide('fade', 200);
		$('.lightsout').fadeOut();
		return false;
	});
});

// Send request to add new claim to database on submit click
function addClaim() {
	$url = $('#pasteURL').val();
	$title = $('input[name=title]').val();
	$desc = $('textarea').val();
	$company = $('#assocCo').tagit('tags');
	if ($company == '') {
		$('#coNote').css('color', 'red');
		$('#coNote').text('*All claims needs to refer to a company')
		return;
	}
	$rating = $('input[name=score]:checked').val();
	$tags = $('#tagsSearch').tagit('tags');
	var tagsObj = new Object();
	for (var i in $tags) {
		tagsObj[i] = $tags[i].value;
	}
	console.log(tagsObj);
	$.ajax({
		type: 'POST',
		url: 'http://127.0.0.1/action/addClaim',
		data: {
			url: $url,
			title: $title,
			desc: $desc,
			company: '206',
			rating: $rating,
			tags: tagsObj
		},
		dataType: 'json',
		success: function(json) {
			alert('Your claim has been submitted!');
			hidePopups();
			window.location.reload();
		},
		error: function() {
			alert('Oops, are you logged in?');
		}
	});
}