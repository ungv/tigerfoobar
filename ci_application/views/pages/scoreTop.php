	<!--Start ScoreTop content-->
	<div id="scoreContainer" class="container">
		<h2>Score</h2>
		<div id="scoreContent" class="content">
			<section id="leftCol">
				<!-- have to make form auto submit with js -->
				<div id="scoreHeader">
					<span id="averageScore"><?=$pageType == 'company' ? $companyInfo['Score'] : $claimInfo['ClaimScore']?></span>
					<span id="scoreInfo">(<?=$pageType == 'company' ? count($companyClaims) . ' claims, [some number of] comments' : '[some number of] ratings, [some number of] what?'?>)</span>
				</div>
	<!--End ScoreTop content-->