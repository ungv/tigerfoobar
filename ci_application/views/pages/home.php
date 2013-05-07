<script type="text/javascript">
$(document).ready(function() {
	$.ajax('http://webhost.ischool.uw.edu/~bc28/dbconnection.php', {
		success: function() {
			alert('yay');
		},
		error: ajaxError
	});
});

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
</script>

<div id="main">
	Front Page
	<ul>
	</ul>
</div>