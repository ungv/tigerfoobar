<div id="main">
	<h1><?=$companyName?></h1>
	<div id="tags">
		<span>Industries:</span>
		<ul id="industryTags">
		<?php
		foreach ($companyTags AS $tag) {
			?>
			<li><?=$tag['Name']?></li>
			<?php
		}
		?>
		</ul>
	</div>
	<div class="container">
		<h2>Score</h2>
		<div id="scoreContent" class="expanded">
			<section id="leftCol">
				<!-- have to make form auto submit with js -->
				<div id="scoreHeader">
					<span id="averageScore"><?=$companyScore?></span> <span id="scoreInfo">(30 claims, 976 opinions)</span>
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
						<input type='radio' id="radio<?=$i?>" name='score' value='<?=$i?>'><label class="scoreBox" for="radio<?=$i?>" value="<?=$i-3?>"> </label>
						<?php
					}
					?>					
					</div>
				</div>
				
			</section>

			<section id="rightCol">
				<div id="relatedTagsHeader">
					<span>Related claim tags for <?=$companyName?></span>
				</div>
				<div id="relatedTagsContainer">
					<ul id="claimPopTags">
						<?php
						foreach ($companyTags AS $tag) {
							?>
							<li><?=$tag['Name']?></li>
							<?php
						}
						?>
					</ul>
				</div>
			</section>
		</div>
	</div>
	
	<h2 class="lowRatedClaims">Low Rated Claims</h2>
	<h2 class="highRatedClaims">High Rated Claims</h2>
	
<?php
foreach($companyClaims AS $claim) {
	if ($claim['Score'] < 0) {
	?>
	<div class="lowRatedClaims">
	<?php
	} else if ($claim['Score'] > 0) {
	?>		
	<div class="highRatedClaims">
	<?php
	}
	?>	
		<div class="claimContainer">
			<div class="leftContainer">			
				<div class="claimScore">
					<?=$claim['Score']?>
				</div>
				<div class="claimVotes">
					104
				</div>
			</div>
			<div class="rightContainer">
				<div class="claimTitle">
					<a href="/claim/<?=$claim['ClaimID']?>"><?=$claim['Title']?></a>
				</div>
				<div class="claimTags">
					Tags: PowermacG4, safety ...
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
</div>