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

#scoreMap, #scoreDistrubution{
	overflow: hidden;
}

#scoreDistrubution{
	width: 280px;
	height: 80px;
}

#scoreDistrubution p{
	width: 40px;
	height: 80px;
	float: left;
	background-color: darkgrey;
	background-image: url(img/kudosCircles.png);
	background-size: 101%;
	position: relative;
	background-position: -1px -1px;
}

#scoreControl label {
	width: 40px;
	height: 40px;
	float: left;
	opacity: .8;
}

#scoreControl label:hover {
	opacity: 1;
}

#scoreControl input {
	display: none;
}

.n3 {
	background-color: #FF4900;
} 
.n2 {
	background-color: #FF7640;
}

.n1 {
	background-color: #FF9B73;
}

.zero {
	background-color: #FEF5CA;
}

.p1 {
	background-color: #61D7A4;
}

.p2 {
	background-color: #36D792;
}

.p3 {
	background-color: #00AF64;
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

	// $('#scoreControl').buttonset();

	// // Kudos Colors       -3         -2         -1         0          +1         +2         +3
	// var kudosColors = ['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#61D7A4', '#36D792', '#00AF64'];

	// for (var i = 0; i <= 6; i++) {
	// 	$("#scoreControl label").eq(i).css("background-color", kudosColors[i]);
	// 	$("#scoreControl label").eq(i).css("background-image", "none");
	// 	$("#scoreControl label").eq(i).css("border-radius", 0);
	// 	$("#scoreControl label").eq(i).css("opacity", 0.8);
	// }
	
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
				<div id='scoreDistrubution'>
<?php
				for ($i=0; $i <=6 ; $i++) { 
?>
					<p style='top: <?=7	*$i?>px'></p>
<?php
				}
?>	
				</div>

				<div id="scoreControl">
<?php
$kudosColors = array('n3', 'n2', 'n1', 'zero', 'p1', 'p2', 'p3');

				for ($i=0; $i <=6 ; $i++) { 
?>
					<input type='radio' id="radio<?=$i?>" name='score' value='<?=$i?>'><label class="<?=$kudosColors[$i]?>" for="radio<?=$i?>"> </label>
<?php
				}
?>					
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