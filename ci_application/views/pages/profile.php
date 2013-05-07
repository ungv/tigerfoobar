<style type="text/css">
td {
	width: 100px;
	height: 100px;
	/* background-color should be applied through class set by its score 
	background-color: rgb(111, 255, 249); */
	padding: 2px;
	opacity: 0.8;
}

td:hover {
	opacity: 1;
}

.rating {
	float: left;
	margin: 10px;
}
</style>

<script type="text/javascript">
var kudosColors = ['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#61D7A4', '#36D792', '#00AF64'];
$(document).ready(function() {
	// Kudos Colors       -3         -2         -1         0          +1         +2         +3
	$.ajax({
		type: 'GET',
		url: 'http://webhost.ischool.uw.edu/~bc28/dbconnection.php',
		contentType: 'jsonp',
		data: {
			sql: "SELECT *, cl.Score AS ClaimScore, co.Score AS CoScore FROM Claim cl JOIN Company co ON cl.CompanyID = co.CompanyID"
		},
		dataType: 'jsonp',
		success: function(json) {
			injectSubmissions(json);
		},
		error: ajaxError
	});
});

function injectSubmissions(json) {
	$.each(json, function(i, claim) {
		$a = $('<a>').attr('href', 'claim').text(claim.Title); // temporary link to claim template page
		$td = $('<td>').attr('class', claim.ClaimScore).attr('style', 'background-color:' + kudosColors[parseInt(claim.ClaimScore) + 3]);
		$td.append($a);
		$('table').find('tr').append($td);
	});
	$.each($('td'), function() {
		var thisRating = parseInt($(this).attr('class'));
		$(this).attr('style', 'background-color:' + kudosColors[parseInt(claim.ClaimScore) + 3]);
	});
}

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
	<div id="user">
		<h1>user123</h1>
	</div>
	
	<div id="submissions">
		<h3>Submissions:</h3>
		<table>
			<tr>
			<!-- inject boxes here -->
			<!-- placeholder boxes to see what it looks like with more
				<td class="1"><a href="claim">Apple plans to build $5 billion new headquarters in Cupertino</a></td>
				<td class="-3"></td>
				<td class="3"></td>
				<td class="-1"></td>
				<td class="-2"></td>
				<td class="1"></td>
				<td class="2"></td>
				<td class="1"></td>
				<td class="2"></td>
			</tr>
			<tr>
				<td class="-1"></td>
				<td class="3"></td>
				<td class="2"></td>
				<td class="-1"></td>
				<td class="2"></td>
				<td class="3"></td>
				<td class="-1"></td>
				<td class="-2"></td>
				<td class="-1"></td>
			</tr>
			<tr>
				<td class="1"></td>
				<td class="1"></td>
				<td class="3"></td>
				<td class="-1"></td>
				<td class="2"></td>
				<td class="-2"></td>
				<td class="2"></td>
				<td class="1"></td>
				<td class="1"></td>
			-->
			</tr>
		</table>
	</div>
	
	<div id="comments">
		<h3>Comments:</h3>
		<div class="threadContainer">
			<div class="rating">+3</div>
			<div class="thread">
				<h3>Apple plans to build $5 billion new headquarters in Cupertino</h3>
				<p>I’d much rather see the money put into the building than back to those greedy wall-streeter hands...</p>
			</div>
		</div>
	</div>
</div>