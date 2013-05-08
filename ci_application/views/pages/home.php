<?php
	/* $db = new PDO("mysql:dbname=bc28_tiger;host=webhost.ischool.uw.edu", "bc28_admin", "tiger123");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * 
			FROM Users";
	$query = $db->prepare($sql);
	$query->execute($params);
	$rows = $query->fetchAll(PDO::FETCH_ASSOC); */
?>

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

#pasteURL {
	width: 180px;
	height: 20px;
}

#urlSubmit {
	/* background: url('http://webhost.ischool.uw.edu/~bc28/submittingClaim.png');
	background-repeat: no-repeat; */
	background-color: #D1D2D4;
	width: 780px;
	height: 450px;
	z-index: 100;
	position: absolute;
	margin-left: 70px;
	padding: 10px;
}

#urlSubmit input, textarea {
	margin-bottom: 5px;
}

#submit, #cancel {
	float: right;
}

.full {
	width: 100%;
}

.half {
	width: 50%;
}

.quarter {
	width: 25%;
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

$(function() {
	$("#pasteURL").bind('paste', function() {
		$('#urlSubmit').show(200);
		$(this).animate({"width": "100%",}, "fast" );
	});
	$("#cancel").click(function() {
		$('#urlSubmit').hide(200);
		$("#pasteURL").animate({"width": "180px",}, "fast" );
		$("#pasteURL").val('');
		return false;
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
	<div id="urlContainer">
		<input style="" id="pasteURL" placeholder="Paste URL to a new article"/>
	</div>
	
	<div id="urlSubmit" style="display:none;">
		<input class="full" type="text" placeholder="Title"/>
		<textarea rows="4" cols="69%" placeholder="Your comments on this article"></textarea>
		<input class="quarter" style="vertical-align: top;" type="text" placeholder="Associated Company"/>
		<input class="full" type="text" placeholder="Tags"/>
		<input type="submit" id="submit" value="Submit"/><input type="submit" id='cancel' value="cancel"  />
	</div>
	
	<ul>
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
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>	
</div>