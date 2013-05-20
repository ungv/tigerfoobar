	<!--Start Evidence Content-->
	<div id="evidenceContainer" class="container">
		<h2>Evidence</h2>
		<div id='evidenceContent' class="content">
			<dl>
				<dt>URL: </dt>
					<dd><a href="<?=$claimInfo['Link']?>"><?=$claimInfo['Link']?></a></dd>
				<dt>Synopsis: </dt>
					<dd><?=$claimInfo['Description']?></dd>
			</dl>
			<hr>
			<dl>
				<dt>By: </dt>
					<dd><a href="/profile/<?=$claimInfo['UserID']?>"><?=$claimInfo['UserName']?></a></dd>
				<dt>Submitted: </dt>
					<dd><?=$claimInfo['ClaimTime']?></dd>
			</dl>
		</div>
	</div>
	<!--End Evidence Content-->