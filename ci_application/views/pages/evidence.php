	<!--Start Evidence Content-->
	<!-- <img id='flagButton' class='tooltip' src="/img/flag.png" 
		title='This claim is 
			<a id="flagNoncredible">Noncredible</a> or 
				<a id="flagWrong">Wrong Company</a>'> -->
	
	<div id="evidenceContainer" class="container">
		<div id='evidenceContent' class="content">
			<dl>
				<dt>Synopsis </dt>
					<dd><?=$claimInfo['Description']?></dd>
				<dt>Source </dt>
					<dd><a href="<?=$claimInfo['Link']?>" target="_blank"><?=parse_url($claimInfo['Link'])['host']?></a></dd>
				<dt>Company </dt>
					<dd><a href="/company/<?=$claimInfo['CompanyID']?>"><?=$claimInfo['CoName']?></a></dd>
			</dl>
			<hr>
		<img id='flagButton' class='tooltip' src="/img/flag.png" 
		title='This claim is 
			<a id="flagNoncredible">Noncredible</a> or 
				<a id="flagWrong">Wrong Company</a>'>		
			<dl>
				<dt>Submitted </dt>
					<dd><?=date("F j, Y", strtotime($claimInfo['ClaimTime']))?>, by <a href="/profile/<?=$claimInfo['UserID']?>"><?=$claimInfo['UserName']?></a></dd>
			</dl>
		<?php if ($pageType != 'company') {	//loading claim
				$objectid = $claimInfo['ClaimID'];
				$tagtype = 'Claim Tag';
				$plural = 'Claim Tags';
				$list = $claimTags;
			?>
			<hr>
			<dl id="tagSection">
				<dt id="tagSectionIndicator"><?=$plural ?>:</dt>
				<ul id="taglist" tagtype="<?=$tagtype?>" objectid="<?=$objectid?>">
					<?php
					foreach ($list AS $tag) {
					?>
							<li<?php if($tag['uservoted']) { ?> class="userVoted" <?php } ?>>
								<span class="tagName"><a href="/tag/<?=$tag['TagsID']?>"><?=$tag['Name']?></a></span>
								<span>(</span>
									<span class="tagTotal"><?=$tag['votes']?></span>
								<span>)</span>
								<span class="tagUpvote" tagtype="<?=$tagtype?>" tagid="<?=$tag['TagsID']?>" objectid="<?=$objectid?>" voted="<?=$tag['uservoted']?>" title='Approve tag'>
									<?php if($tag['uservoted']) { ?>
											-
									<?php }else { ?>
											+
									<?php } ?>
								</span>
							</li>
					<?php } ?>
				</ul>
				<a id="addTag" href="#" <?php if(!$isLogged) { ?>style="display:none;"<?php } ?> title='Add new tag'>+</a>
				<div id="newTagPopup" style="display:none;">
					<input id="newtag_name" tagtype="<?=$tagtype?>" type="text" placeholder="Type a tag name"/>
				</div>
			</dl>

		<?php } ?>
		</div>
	</div>
	<!--End Evidence Content-->