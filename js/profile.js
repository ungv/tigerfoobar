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