<div id="listview">
	<h2 class="lowRatedClaims">Low Rated Claims</h2>
	<h2 class="highRatedClaims">High Rated Claims</h2>
	
	<ul class="lowRatedClaims">
	<?php
	foreach($companyClaimsNeg AS $claim) {
	?>
		<li class="claimContainer">
			<div class="leftContainer">			
				<div class="claimScore">
					<?=$claim['Score']?>
				</div>
				<div class="claimVotes">
					<?=$claim['noRatings'] . ($claim['noRatings'] == 1 ? ' rating' : ' ratings')?>
				</div>
			</div>
			<div class="rightContainer">
				<div class="claimTitle">
					<a href="/claim/<?=$claim['ClaimID']?>"><?=$claim['Title']?></a>
				</div>
				<div class="claimTags">
					Tags: [need help with this query]
				</div>
			</div>
		</li>
	<?php
	}	
	?>
	</ul>

	<ul class="highRatedClaims">
	<?php
	foreach($companyClaimsPos AS $claim) {
	?>		
		<li class="claimContainer">
			<div class="leftContainer">			
				<div class="claimScore">
					<?=$claim['Score']?>
				</div>
				<div class="claimVotes">
					<?=$claim['noRatings'] . ($claim['noRatings'] == 1 ? ' rating' : ' ratings')?>
				</div>
			</div>
			<div class="rightContainer">
				<div class="claimTitle">
					<a href="/claim/<?=$claim['ClaimID']?>"><?=$claim['Title']?></a>
				</div>
				<div class="claimTags">
					Tags: [need help with this query]
				</div>
			</div>
		</li>
	<?php
	}
	?>
	</div>
</div>