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
			} else {
				foreach ($companyClaims as $claim) {
					$scoreValue = $claim['Score'];
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
		for ($i=0; $i <=6 ; $i++) { 
			?>
			<input type='radio' id="radio<?=$i?>" name='score' value='<?=$i-3?>'>
			<label class="scoreBox" for="radio<?=$i?>" value="<?=$i-3?>"></label>
			<?php
		}
		?>
		</div>
	</div>
	<!--End Score content-->