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