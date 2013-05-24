<?php
if ($pageType == 'company') {
	?>
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
}
?>