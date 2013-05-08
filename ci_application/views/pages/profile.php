<style type="text/css">
ul {
	float: left;
	width: 960px;
}

li {
	width: 150px;
	height: 90px;
	border: 1px
	padding: 2px;
	opacity: 0.8;
	list-style-type: none;
	float: left;
}

li:hover {
	opacity: 1;
}

.rating {
	float: left;
	margin: 10px;
}

#submissions, #comments {
	float: left;
}
</style>

<script type="text/javascript">
// Kudos Colors       -3         -2         -1         0          +1         +2         +3
var kudosColors = ['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#61D7A4', '#36D792', '#00AF64'];
$(document).ready(function() {
	$.ajax({
		type: 'GET',
		url: 'http://webhost.ischool.uw.edu/~bc28/service.php',
		data: {
			sql: "SELECT *, cl.Score AS ClaimScore, co.Score AS CoScore FROM Claim cl JOIN Company co ON cl.CompanyID = co.CompanyID"
		},
		contentType: 'jsonp',
		dataType: 'jsonp',
		success: function(json) {
			injectSubmissions(json);
		},
		error: ajaxError
	});
	$.each($('li'), function() {
		$(this).attr('style', 'background-color:' + kudosColors[Math.ceil(Math.random() * 6)]);
	});
});

function injectSubmissions(json) {
	$.each(json, function(i, claim) {
		$a = $('<a>').attr('href', 'claim').text(claim.Title); // temporary link to claim template page
		$li = $('<li>').attr('class', claim.ClaimScore).attr('style', 'background-color:' + kudosColors[parseInt(claim.ClaimScore) + 3]);
		$li.append($a);
		$('ul').prepend($li);
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
		<ul>
			<!-- inject boxes here -->
			<!-- placeholder boxes to see what it looks like with more -->
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
		</ul>
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