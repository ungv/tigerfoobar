	<!--Start Evidence Content-->
	<div id="evidenceContainer" class="container">
		<h2>Evidence</h2>
		<div id='evidenceContent' class="content">
			<dl>
				<dt>URL: </dt>
					<dd><a href="<?=$claimInfo[0]['Link']?>"><?=$claimInfo[0]['Link']?></a></dd>
				<dt>Synopsis: </dt>
					<dd><?=$claimInfo[0]['Description']?></dd>
			</dl>
			<hr>
			<dl>
				<dt>By: </dt>
					<dd><a href="/profile/<?=$claimInfo[0]['UserID']?>"><?=$claimInfo[0]['UserName']?></a></dd>
				<dt>Submitted: </dt>
					<dd><?=$claimInfo[0]['ClaimTime']?></dd>
			</dl>
		</div>
	</div>
	<!--End Evidence Content-->