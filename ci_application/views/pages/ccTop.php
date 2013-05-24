<?php
if ($pageType != 'tag') {

	// Victor (5-20-13): I made the id and class names uniform across 'claim' and 'company' pages to give it the same css styling. I also made those changes in the JS so the scripts are still working.
	?>
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
		<ul id="taglist" objectid="<?=$objectid?>">
			<?php
			foreach ($list AS $tag) {
			?>
					<li<?php if($tag['uservoted']) { ?> class="userVoted" <?php } ?>>
						<span class="tagName"><?=$tag['Name']?></span>
						<span>(</span>
							<span class="tagTotal"><?=$tag['votes']?></span>
						<span>)</span>
						<span class="tagUpvote" tagtype="Industry" tagid="<?=$tag['TagsID']?>" objectid="<?=$objectid?>" voted="<?=$tag['uservoted']?>">
							<?php if($tag['uservoted']) { ?>
									-
							<?php }else { ?>
									+
							<?php } ?>
						</span>
					</li>
			<?php } ?>
		</ul>
		<a id="addTag" href="#">+</a>
		<div id="newTagPopup" style="display:none;">
			<input id="newtag_name" tagtype="Industry" type="text" placeholder="Type an Industry name"/>
		</div>	
	</div>
	<?php
} else { // Currently viewing 'tag' pages

}
?>
<!--End of Top-->