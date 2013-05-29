	<div id="tagSection">
	<?php
	if ($pageType == 'company') {	//loading industries
		$objectid = $companyInfo['CompanyID'];
		$tagtype = 'Industry';
		$list = $companyTags;
	?>
		<h1><?=$companyInfo['Name']?></h1>
		<span>Industries:</span>
	<?php
	}else {							//loading claim tags
		$objectid = $claimInfo['ClaimID'];
		$tagtype = 'Claim Tag';
		$list = $claimTags;
	?>
		<h1><a href="<?=$claimInfo['Link']?>" target="_blank"><?=$claimInfo['ClaimTitle']?></a></h1>
		<span>Tags:</span>
	<?php
	}
	?>
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
		<a id="addTag" href="#" title='Add new tag'>+</a>
		<div id="newTagPopup" style="display:none;">
			<input id="newtag_name" tagtype="Industry" type="text" placeholder="Type an Industry name"/>
		</div>
	</div>
<!--End of Top-->