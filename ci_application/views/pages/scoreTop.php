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
						<?= //if on company page
							$pageType == 'company' ?
							//then print number of company's claims
							$companyClaims[0]['numScores'] . ' claims, [some number of] comments' : 
							//else on claim page, print number of claim's ratings
							$scores[0]['Total'] . 
								//if claim only has one rating
								($scores[0]['Total'] == 1 ? 
								//then non-plural
								' rating, ' : 
								//else plural
								' ratings, ') . 
							count($comments) . 
								//if claim only has one comment
								(count($comments) == 1 ? 
								//then non-plural
								' comment' : 
								//else plural
								' comments')?>
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