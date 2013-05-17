$(document).ready(function() {
	$('#newClaimForm').validate({
		rules: {
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

	$('#urlButton').click(function() {
		$('#urlButton').hide('fade', 200);
		$('#urlInput').show('fade', 600);
	});
	
	$("#pasteURL").bind('paste', function() {
		$('#urlInput').removeClass('quarter').addClass('half');
		$('#urlSubmit').show(200);
	});
	$(".cancelButton").click(function() {
		$('#urlInput').removeClass('half').addClass('quarter');
		$('#urlSubmit').hide(200);
		$("#pasteURL").val('');
		$('#urlButton').show('fade', 600);
		$('#urlInput').hide('fade', 200);
		return false;
	});
});


//	Used for error-checking ajax calls
function ajaxError(jqxhr, type, error) {
	var msg = "An Ajax error occurred!\n\n";
	if (type == 'error') {
		if (jqxhr.readyState == 0) {
			// Request was never made - security block?
			msg += "Looks like the browser security-blocked the request.";
		} else {
			// Probably an HTTP error.
			msg += 'Error code: ' + jqxhr.status + "\n" + 
						 'Error text: ' + error + "\n" + 
						 'Full content of response: \n\n' + jqxhr.responseText;
		}
	} else {
		msg += 'Error type: ' + type;
		if (error != "") {
			msg += "\nError text: " + error;
		}
	}
	alert(msg);
}