<?php
	$tagtype;
	$plural;
	if ($pageType == 'company') {	//loading industries
		$objectid = $companyInfo['CompanyID'];
		$tagtype = 'Industry';
		$plural = 'Industries';
		$list = $companyTags;
	?>
		<h1><?=$companyInfo['Name']?></h1>
		<div id="evidenceContainer" class="container">
			<div id='evidenceContent' class="content">
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
							<li>
								<a id="addTag" href="#" <?php if(!$isLogged) { ?>style="display:none;"<?php } ?> title='Add new tag'>+</a>
								<div id="newTagPopup" style="display:none;">
									<input id="newtag_name" tagtype="<?=$tagtype?>" type="text" placeholder="Type a tag name"/>
								</div>
							</li>
						</ul>
					</dd>
				</dl>
			</div>
		</div>
	<?php
	}else {
		if (isset($thisUser) && $claimInfo['UserID'] == $thisUser) {
	?>
		<div style="position: relative;">
			<input type="text" class="outfocus editBox" style="width: 90%; display: none;">
			<h1><a href="<?=$claimInfo['Link']?>" class="editable" target="_blank"><?=$claimInfo['ClaimTitle']?></a></h1>
			<img class="editbutton" src="/img/contribute_icon.png" title="Edit Title" />
			<button class="submitButton updateEdit">Submit</button>
		</div>
	<?php
		} else {
		?>
			<h1><a href="<?=$claimInfo['Link']?>" class="editable" target="_blank"><?=$claimInfo['ClaimTitle']?></a></h1>
		<?php
		}
	}
	?>
<!--End of Top-->