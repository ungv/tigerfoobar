	<!--Start ScoreTop content-->
	<div id="scoreContainer" class="container">
		<h2>Score</h2>
		<div id="scoreContent" class="content">
			<section id="leftCol">
				<!-- have to make form auto submit with js -->
				<div id="scoreHeader">
					<span id="averageScore"><?=$pageType == 'company' ? 
						$companyInfo['Score'] : $claimInfo[0]['Score'] ?></span>
					<span id="scoreInfo">(30 claims, 976 comments)</span>
				</div>
	<!--End ScoreTop content-->