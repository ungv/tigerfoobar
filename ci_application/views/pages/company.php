<div id="main">
	<h1><?=$pageTitle?></h1>
	<div id="tags">
		<span>Industries:</span>
		<ul id="industryTags">
			<li>tag1</li>
			<li>tag2</li>
			<li>tag3</li>
			<li>tag4</li>
			<li>tag5</li>
			<li>tag6</li>
		</ul>
	</div>
	<div class="container">
		<h2>Score</h2>
		<div id="scoreContent" class="expanded">
			<section id="leftCol">
				<!-- have to make form auto submit with js -->
				<div id="scoreHeader">
					<span id="averageScore">+1.1 average</span> <span id="scoreInfo">(30 claims, 976 opinions)</span>
				</div>
				<div id="controlContainer">
					<div id='scoreDistribution'>
<?php
for ($i=0; $i <=6 ; $i++) { 
?>
						<div style='top: <?=7	*$i?>px'>
<?php
for ($j=0; $j < 4; $j++) { 
?>
							<p class='circle'></p>
<?php
}
?>	
						</div>
<?php
}
?>	
					</div>

					<div id="scoreControl">
	<?php
					for ($i=0; $i <=6 ; $i++) { 
	?>
						<input type='radio' id="radio<?=$i?>" name='score' value='<?=$i?>'><label class="scoreBox" for="radio<?=$i?>"> </label>
	<?php
					}
	?>					
					</div>
				</div>
				
			</section>

			<section id="rightCol">
				<div id="relatedTagsHeader">
					<span>Related claim tags for [COMPANY NAME]</span>
				</div>
				<div id="relatedTagsContainer">
					<ul id="claimPopTags">
						<li>tag1</li>
						<li>tag2</li>
						<li>tag3</li>
						<li>tag4</li>
						<li>tag5</li>
						<li>tag6</li>
						<li>tag7</li>
						<li>tag8</li>
						<li>tag9</li>
						<li>tag10</li>
						<li>tag11</li>
						<li>tag12</li>
						<li>tag13</li>
						<li>tag14</li>
						<li>tag15</li>
						<li>tag16</li>
						<li>tag17</li>
					</ul>
				</div>
			</section>
		</div>
	</div>
	
	
	<div id="lowRatedClaims">
		<h2 id="lowClaimHead">Low Rated Claims</h2>
		<div class="claimContainer">
			<div class="leftContainer">			
				<div class="claimScore">
					-3.0
				</div>
				<div class="claimVotes">
					104
				</div>
			</div>
			<div class="rightContainer">
				<div class="claimTitle">
					Powermac G4 cube catches a fire under heavy usage
				</div>
				<div class="claimTags">
					Tags: PowermacG4, safety ...
				</div>
			</div>
		</div>
	</div>

	<div id="highRatedClaims">
		<h2 id="highClaimHead">High Rated Claims</h2>
		<div class="claimContainer">
			<div class="rightContainer">
				<div class="claimTitle">
					Apple hired third-party to investigate supply line labor conditions
				</div>
				<div class="claimTags">
					Tags: iPhone5, foxconn, laborpractices ...
				</div>
			</div>
			<div class="leftContainer">
				<div class="claimScore">
					+2.4
				</div>
				<div class="claimVotes">
					197
				</div>
			</div>
		</div>
	</div>
</div>