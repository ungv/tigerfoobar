<?php
if ($pageType != 'tag') {

	// Victor (5-20-13): I made the id and class names uniform across 'claim' and 'company' pages to give it the same css styling. I also made those changes in the JS so the scripts are still working.
	?>
	<div id="tagList">
	<?php
	if ($pageType == 'company') {
	?>
		<h1><?=$companyInfo['Name']?></h1>
		<span>Industries:</span>
		<ul id="industryTags" objectid="<?=$companyInfo['CompanyID']?>">
			<?php
			foreach ($companyTags AS $tag) {
			?>
					<li<?php if($tag['uservoted']) { ?> class="userVoted" <?php } ?>>
						<span class="tagName"><?=$tag['Name']?></span>
						<span>(</span>
							<span class="tagTotal"><?=$tag['votes']?></span>
						<span>)</span>
						<span class="tagUpvote" tagtype="Industry" tagid="<?=$tag['TagsID']?>" objectid="<?=$companyInfo['CompanyID']?>" voted="<?=$tag['uservoted']?>">
							<?php if($tag['uservoted']) { ?>
									-
							<?php }else { ?>
									+
							<?php } ?>
						</span>
					</li>
			<?php } ?>
		</ul>
		<a id="addTag" href="#">add industry</a>
		<div id="newTagPopup" style="display:none;">
			<input id="newtag_name" tagtype="Industry" type="text" placeholder="Type an Industry name"/>
		</div>	

	<?php
	} else {
	?>
		<h1><a href="<?=$claimInfo['Link']?>" target="_blank"><?=$claimInfo['ClaimTitle']?></a></h1>
		<span>Tags:</span>
		<ul id="claimTags" objectid="<?=$claimInfo['ClaimID']?>">
			<?php
			foreach ($claimTags AS $tag) {
			?>
				<li <?php if($tag['uservoted']) { ?> class="userVoted" <?php } ?> >
					<span class="tagName"><?=$tag['Name']?></span>
					<span>(</span>
						<span class="tagTotal"><?=$tag['votes']?></span>
					<span>)</span>
					<span class="tagUpvote" tagtype="Claim" tagid="<?=$tag['TagsID']?>" objectid="<?=$claimInfo['ClaimID']?>" voted="<?=$tag['uservoted']?>">
						<?php if($tag['uservoted']) { ?>
								-
						<?php }else { ?>
								+
						<?php } ?>
					</span>
				</li>
			<?php } ?>
		</ul>
		<a id="addTag" href="#">add claim tag</a>
		<div id="newTagPopup" style="display:none;">
			<input id="newtag_name" tagtype="Claim Tag" type="text" placeholder="Type a tag for this claim"/>
		</div>
	<?php
	}
	?>
	</div>
	<?php
} else { // Currently viewing 'tag' pages

}
?>
<!--End of Top-->