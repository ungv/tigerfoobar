	<!--Start Evidence Content-->
	<!-- <img id='flagButton' class='tooltip' src="<?=base_url()?>img/flag.png" 
		title='This claim is 
			<a id="flagNoncredible">Noncredible</a> or 
				<a id="flagWrong">Wrong Company</a>'> -->
	
	<div id="evidenceContainer" class="container">
		<div id='evidenceContent' class="content">
			<dl>
				<dt>Synopsis </dt>
					<dd>
						<div style="position: relative;">
							<input type="text" class="outfocus" style="width: 90%; display: none;">
							<p class="editable"><?=$claimInfo['Description']?></p>
							<img class="editbutton" src="/img/contribute_icon.png" title="Edit Synopsis" />
							<button class="submitButton updateEdit">Submit</button>
						</div>
					</dd>
				<dt>Source </dt>
					<dd><a href="<?=$claimInfo['Link']?>" target="_blank">
						<?php
							$tmp = parse_url($claimInfo['Link']);
							echo($tmp['host']);
						?>
						</a>
					</dd>
				<dt>Company </dt>
					<dd><a href="<?=base_url()?>company/<?=$claimInfo['CompanyID']?>"><?=$claimInfo['CoName']?></a></dd>
			</dl>
			<hr>
		<img id='flagButton' class='tooltip' src="<?=base_url()?>img/flag.png" 
		title='This claim is 
			<a id="flagNoncredible">Noncredible</a> or 
				<a id="flagWrong">Wrong Company</a>'>		
			<dl>
				<dt>Submitted </dt>
					<dd><?=date("F j, Y", strtotime($claimInfo['ClaimTime']))?>, by <a href="<?=base_url()?>profile/<?=$claimInfo['UserID']?>"><?=$claimInfo['UserName']?></a></dd>
			</dl>
		<?php if ($pageType != 'company') {	//loading claim
				$objectid = $claimInfo['ClaimID'];
				$tagtype = 'Claim Tag';
				$plural = 'Claim Tags';
				$list = $claimTags;
			?>
			<hr>
			<dl id="tagSection">
				<dt id="tagSectionIndicator"><?=$plural ?></dt>
				<dd>
					<ul id="taglist" tagtype="<?=$tagtype?>" objectid="<?=$objectid?>">
						<?php
						foreach ($list AS $tag) {
						?>
							<li>
								<div class="fancyTags <?= $tag['uservoted'] ? 'userVoted' : 'notVoted' ?>">
									<span class="tagName"><a href="<?=base_url()?>tag/<?=$tag['TagsID']?>"><?=$tag['Name']?></a></span>
									<span>(</span>
										<span class="tagTotal"><?=$tag['votes']?></span>
									<span>)</span>
									<span class="tagUpvote" tagtype="<?=$tagtype?>" tagid="<?=$tag['TagsID']?>" objectid="<?=$objectid?>" voted="<?=$tag['uservoted']?>" title="<?=$tag['uservoted'] ? 'Disapprove tag' : 'Approve tag'?>">&#9650;</span>
								</div>
							</li>
						<?php } ?>
					<li><a id="addTag" href="#" <?php if(!$isLogged) { ?>style="display:none;"<?php } ?> title='Add new tag'>+</a></li>
					</ul>
				</dd>
				
				<div id="newTagPopup" style="display:none;">
					<input id="newtag_name" tagtype="<?=$tagtype?>" type="text" placeholder="Type a tag name"/>
				</div>
			</dl>

		<?php } ?>
		</div>
	</div>
	<!--End Evidence Content-->