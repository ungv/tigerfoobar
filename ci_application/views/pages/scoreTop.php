	<!--Start ScoreTop content-->
	<div id="scoreContainer" class="container">
		<h2>Score</h2>
		<div id="scoreContent" class="content">
			<section id="leftCol">
				<!-- have to make form auto submit with js -->
				<div id="scoreHeader">
					<span id="averageScore"><?=$pageType == 'company' ? $companyInfo['Score'] : $claimInfo['ClaimScore']?></span>
						<?php
						$string = '';
						if (!empty($scores) || !empty($companyClaims)) {
							$totalNumRatings = 0;
							if ($pageType == 'company') {
								foreach($companyClaims as $claim) {
									$totalNumRatings += $claim['noRatings'];
								}
								$string .= $companyClaims[0]['Total'];
								if ($companyClaims[0]['Total'] == 1)
									$string .= ' claim, ';
								else 
									$string .= ' claims, ';
								$string .= $totalNumRatings;
								if ($totalNumRatings == 1)
									$string .= ' rating';
								else 
									$string .= ' ratings';
							} else {
								$string .= $scores[0]['Total'];
								if ($scores[0]['Total'] == 1)
									$string .= ' rating, ';
								else 
									$string .= ' ratings, ';
								$string .= count($comments);
								if (count($comments) == 1)
									$string .= ' comment';
								else 
									$string .= ' comments';								
							}
						} else {
							?>
							<!-- should never enter this statement since a claim will always have at least one rating -->
							<?php
							$string += 'This claim has not been rated by anyone yet. Submit a rating below!';
						}
						?>
					<span id="scoreInfo">(<?=$string?>)</span>
				</div>
	<!--End ScoreTop content-->