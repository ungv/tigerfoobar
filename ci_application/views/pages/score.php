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
			<input type='radio' id="radio<?=$i?>" name='<?=$pageType == 'claim' ? 'claim' : ''?>score' value='<?=$i-3?>' claimid='<?=$pageType=='claim' ? $claimID : ''?>'>
			<label class="scoreBox tooltip" for="radio<?=$i?>" value="<?=$i-3?>" hasratedthis="<?=$hasRatedThis?>" title='Rate this claim as <strong><?=$i-3?></strong>'></label>
			<?php
		}
		?>
		</div>
	</div>
	</section>

			<section id="rightCol">
				<div id="relatedTagsHeader">
				<?php
				if ($pageType == 'company') {
				?>
					<span>Tags applied to related claims</span>
				<?php
				} else {
				?>
					<span><a href="/company/<?=$claimInfo['CompanyID']?>"><?=$claimInfo['CoName']?></a></span><br/>
					<span style="font-size: 10pt;">related claims:</span>
				<?php
				}
				?>
				</div>
				<div id="relatedTagsContainer">
				<?php
				if ($pageType == 'company') {
					?>
					<ul id="claimPopTags">
						<?php
						// need to get most popular claims for this company, then get their most popular tags
						foreach ($companyTags AS $tag) {
							?>
							<li>[need help with this query]<?//=$tag['Name']?></li>
							<?php
						}
						?>
					</ul>
					<?php
				}
				?>
	<!--End Score content-->