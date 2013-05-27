	<!--Start Evidence Content-->
	<img id='flagButton' class='tooltip' src="/img/flag.png" 
		title='This claim is
			<a id="flagNoncredible">Noncredible</a> or 
				<a id="flagWrong">Wrong Company</a>'>
	
	<div id="evidenceContainer" class="container">
		<div id='evidenceContent' class="content">
			<dl>
				<dt>URL: </dt>
					<dd><a href="<?=$claimInfo['Link']?>"><?=$claimInfo['Link']?></a></dd>
				<dt>Synopsis: </dt>
					<dd><?=$claimInfo['Description']?></dd>
			</dl>
			<hr>
			<dl>
				<dt>Submitted: </dt>
					<dd><?=date("F j, Y", strtotime($claimInfo['ClaimTime']))?></dd>
				<dt>By: </dt>
					<dd><a href="/profile/<?=$claimInfo['UserID']?>"><?=$claimInfo['UserName']?></a></dd>
			</dl>
		</div>
	</div>
	<!--End Evidence Content-->