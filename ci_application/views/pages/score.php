	<?php
	// This view is being used on "addClaim", "claim", and "company pages"
	?>
	<!--Start Score content-->
	<div id="controlContainer">
		<div id='scoreDistribution'>
			<?php
			$topPos = 0;
			if ($pageType == 'claim') {
				foreach ($scores as $score) {
					$scoreValue = $score['Value'];
					$scorePer = $score['noRatings'] / $score['Total'];
					if ($scorePer <= 0.25) {
						$topPos = 45;
					} else if ($scorePer <= 0.50) {
						$topPos = 30;
					} else if ($scorePer <= 0.75) {
						$topPos = 15;
					}
					?>
					<div class="box<?=$scoreValue + 3?>" style="top: <?=$topPos?>px">
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
			} else if ($pageType == 'company') {
				foreach ($companyClaims as $claim) {
					$scoreValue = ceil($claim['Score']);
					$scorePer = $claim['noRatings'] / $claim['Total'];
					if ($scorePer <= 0.25) {
						$topPos = 45;
					} else if ($scorePer <= 0.50) {
						$topPos = 30;
					} else if ($scorePer <= 0.75) {
						$topPos = 15;
					}
					?>
					<div class="box<?=$scoreValue + 3?>" style="top: <?=$topPos?>px">
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
			}
			?>

		</div>

		<div id="scoreControl">
		<?php

		$tooltips = array(
			"This claim shows the company&#39;s behavior is <br><strong>Reprehensible</strong>. Rate this claim as <strong>-3</strong>",
			"This claim shows the company&#39;s behavior is <br><strong>Terrible</strong>. Rate this claim as <strong>-2</strong>",
			"This claim shows the company&#39;s behavior is <br><strong>Bad</strong>. Rate this claim as <strong>-1</strong>",
			"This claim is <strong>Trivial</strong>. Rate this claim as <strong>0</strong>",
			"This claim shows the company&#39;s behavior is <br><strong>Good</strong>. Rate this claim as <strong>+1</strong>",
			"This claim shows the company&#39;s behavior is <br><strong>Great</strong>. Rate this claim as <strong>+2</strong>",
			"This claim shows the company&#39;s behavior is <br><strong>Exemplary</strong>. Rate this claim as <strong>+3</strong>"
		);
		for ($i=0; $i <=6 ; $i++) { 
			$hasRatedThis = 0;
			// If user has previously rated this claim, high the box they've rated
			if ($pageType == 'claim' && !empty($userRating) && $userRating->Value == $i-3) {
				$hasRatedThis = 1;
			} else if ($pageType == 'home' && $i == 3) {
			?>
				<style type="text/css">
					#controlContainer {
						margin-left: 25px;
					}
					label[for='radio3'] {
						display: none;
					}
				</style>
			<?php
			}
			?>
			<input type="radio" id="radio<?=$i?>" name="<?=$pageType == 'claim' ? 'claim' : ''?>score" value="<?=$i-3?>" claimid="<?=$pageType=='claim' ? $claimID : ''?>">
			<label class="scoreBox tooltip" for="radio<?=$i?>" value="<?=$i-3?>" hasratedthis="<?=$hasRatedThis?>" title="<?=$tooltips[$i]?>" name="<?=$pageType == 'claim' || $pageType == 'home' ? 'claim' : ''?>score"></label>
			<?php
		}
		?>
		</div>
	</div>
	<!--End Score content-->