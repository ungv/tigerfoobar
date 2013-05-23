	<!--Start ScoreTop content-->
	<div id="scoreContainer" class="container">
		<h2>Score</h2>
		<div id="scoreContent" class="content">
			<section id="leftCol">
				<!-- have to make form auto submit with js -->
				<div id="scoreHeader">
					<span id="averageScore"><?=$pageType == 'company' ? $companyInfo['Score'] : $claimInfo['ClaimScore']?></span>
					<span id="scoreInfo">(
						<?php
						if (!empty($scores) || !empty($companyClaims)) {
						?>
						<?=$pageType == 'company' ? $companyClaims[0]['numScores'] . ' claims, [some number of] comments' : $scores[0]['Total'] . ($scores[0]['Total'] == 1 ? ' rating, ' : ' ratings, ') . count($comments) . (count($comments) == 1 ? ' comment' : ' comments')?>
						<?php
						} else {
						?>
							This claim has not been rated by anyone yet. Submit a rating below!
						<?php
						}
						?>
					)</span>
				</div>
	<!--End ScoreTop content-->