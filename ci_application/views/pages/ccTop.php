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
		<ul id="industryTags" companyid="<?=$companyInfo['CompanyID']?>">
			<?php
			foreach ($companyTags AS $tag) {
			?>
					<li<?php if($tag['uservoted']) { ?> class="uservoted" <?php } ?>>
						<span class="tagName"><?=$tag['Name']?></span>
						<span>(</span>
							<span class="tagTotal"><?=$tag['votes']?></span>
						<span>)</span>
						<span class="tagUpvote" tagid="<?=$tag['TagsID']?>" companyid="<?=$companyInfo['CompanyID']?>" voted="<?=$tag['uservoted']?>">
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
			<input id="newindustry_name" type="text" placeholder="Type an Industry name"/>
		</div>	

	<?php
	} else {
	?>
		<h1><a href="<?=$claimInfo['Link']?>" target="_blank"><?=$claimInfo['ClaimTitle']?></a></h1>
		<span>Tags:</span>
		<ul id="claimTags" claimid="<?=$claimInfo['ClaimID']?>">
			<?php
			foreach ($claimTags AS $tag) {
			?>
				<li <?php if($tag['uservoted']) { ?> class="uservoted" <?php } ?> >
					<span class="tagName"><?=$tag['Name']?></span>
					<span>(</span>
						<span class="tagTotal"><?=$tag['votes']?></span>
					<span>)</span>
					<span class="tagUpvote" tagid="<?=$tag['TagsID']?>" companyid="<?=$claimInfo['CompanyID']?>" voted="<?=$tag['uservoted']?>">
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
			<input id="newclaimtag_name" type="text" placeholder="Type a tag for this claim"/>
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