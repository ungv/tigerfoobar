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
// Kudos Colors       -3         -2         -1         0          +1         +2         +3
var kudosColors = ['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#61D7A4', '#36D792', '#00AF64'];
$(document).ready(function() {
	$.each($('td'), function() {
		var thisRating = parseInt($(this).attr('class'));
		/* if (thisRating == 3) thisRating -= 1;
		else if (thisRating == -3) thisRating += 1; */
		$(this).css('background-color', kudosColors[thisRating + 3]);
	});
});
</script>

<div id="main">
	<div id="user">
		<h1>user123</h1>
	</div>
	
	<div id="submissions">
		<h3>Submissions:</h3>
		<table>
			<tr>
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