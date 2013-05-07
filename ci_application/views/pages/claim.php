<style type="text/css">
.container > div {
	border: 1px solid rgb(213, 213, 213);
	padding: 10px;
}

.container h2 {
	width: 140px;
}

#user {
	float: right;
	/*padding: 10px;
	text-align: right;
	margin: 0 40px 0 -10px !important;
	width: 889px;
	border: 1px solid grey;
	border-left: none;*/
}

#evidenceContent {
	border-left: 5px solid blue;
}

#scoreContent {
	border-left: 5px solid green;

}

#discussionContent {
	border-left: 5px solid red;

}

.ui-state-default {
	border: none !important;
	/*background: */
}

/*#radio1 {
	background: #7F7734 !important;
}*/

.ui-button .ui-button-text {
	color: white;
	font-weight: bold;
	width: 25px;
	height: 20px;
}

.ui-state-hover {
	opacity: 1 !important;
}

#scoreMap {
	width: 384px;
}

#scoreDistrubution {
	height: 50px;
	overflow: hidden;
}


</style>

<script type="text/javascript">
$(document).ready(function() {
	$('.container h2').click(function() {
		$(this).siblings().slideToggle(200, function() {
			$origText = $(this).siblings().text().substring(0, $(this).siblings().text().length - 1);
			if ($(this).siblings().attr('class') == 'expanded') {
				$(this).siblings().text($origText + '-')
				$(this).siblings().attr('class', 'collapsed');
			} else {
				$(this).siblings().text($origText + '+')
				$(this).siblings().attr('class', 'expanded');
			}
		});
	});

	$('#scoreControl').buttonset();

	// Kudos Colors       -3         -2         -1         0          +1         +2         +3
	var kudosColors = ['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#61D7A4', '#36D792', '#00AF64'];

	for (var i = 0; i <= 6; i++) {
		$("#scoreControl label").eq(i).css("background-color", kudosColors[i]);
		$("#scoreControl label").eq(i).css("background-image", "none");
		$("#scoreControl label").eq(i).css("border-radius", 0);
		$("#scoreControl label").eq(i).css("opacity", 0.8);
	}
	
	$('.container h2').mouseover(function() {
		$(this).attr('style', 'background-color: rgb(233, 233, 233)');
	}).mouseout(function() {
		$(this).attr('style', 'background-color: white');
	});
});
</script>

<div id="main">
	<div id="title">
		<h1>Apple plans to build $5 billion new headquarters in Cupertino</h1>
		<div id="user">Submitted 2 months ago by <a href="profile.php">thisDude</a></div>
		<div id="url">URL: <a href="http://www.google.com">Google</a></div>
		<div id="synopsis">Synopsis: Apple’s massive new spaceship-style headquarters is not on-budget or on schedule, according to a new report at Bloomberg...</div>
		<div id="tags">Tags:
			<?php
				// loop through tags table to display them in colored divs
			?>
		</div>
	</div>
	
	<div class="container">
		<h2>Score -</h2>
		<div id="scoreContent" class="expanded">
			<section id='scoreMap'>
				<!-- have to make form auto submit with js -->
				<div id="scoreControl">
					<input type='radio' id="radio1" name='score' value='0'><label for="radio1">-</label>
					<input type='radio' id="radio2" name='score' value='1'><label for="radio2"> </label>
					<input type='radio' id="radio3" name='score' value='2'><label for="radio3"> </label>
					<input type='radio' id="radio4" name='score' value='3'><label for="radio4">0</label>
					<input type='radio' id="radio5" name='score' value='4'><label for="radio5"> </label>
					<input type='radio' id="radio6" name='score' value='5'><label for="radio6"> </label>
					<input type='radio' id="radio7" name='score' value='6'><label for="radio7">+</label>
				</div>

				<div id='scoreDistrubution'>

				</div>
			</section>

			<div id="linkedTo">
				
			</div>
		</div>
	</div>
	
	<div class="container">
		<h2>Discussion -</h2>
		<div id="discussionContent" class="expanded">
			<div id="rating">+3</div>
		</div>
	</div>
</div>