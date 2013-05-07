<?php
	/* $db = new PDO("mysql:dbname=bc28_tiger;host=webhost.ischool.uw.edu", "bc28_admin", "tiger123");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * 
			FROM Users";
	$query = $db->prepare($sql);
	$query->execute($params);
	$rows = $query->fetchAll(PDO::FETCH_ASSOC); */
?>

<script type="text/javascript">
$(document).ready(function() {
	$.ajax({
		type: 'GET',
		url: 'http://webhost.ischool.uw.edu/~bc28/dbconnection.php',
		data: {
			sql: "SELECT * FROM Claim"
		},
		contentType: 'jsonp',
		dataType: 'jsonp',
		success: function(json) {
			
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