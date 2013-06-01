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
	<?php
	}else {							//loading claim tags
		$objectid = $claimInfo['ClaimID'];
		$tagtype = 'Claim Tag';
		$plural = 'Claim Tags';
		$list = $claimTags;
	?>
		<h1><a href="<?=$claimInfo['Link']?>" target="_blank"><?=$claimInfo['ClaimTitle']?></a></h1>
	<?php
	}
	?>
	<div id="tagSection">
		<span><?=$plural ?>:</span>
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
			<input id="newtag_name" tagtype="Industry" type="text" placeholder="Type an Industry name"/>
		</div>
	</div>
<!--End of Top-->