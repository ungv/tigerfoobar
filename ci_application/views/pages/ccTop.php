<!--Start #main content-->
<div id="main">
	<h1><?=$pageType == 'company' ? $companyInfo['Name'] : $claimInfo['ClaimTitle']?></h1>
<?php
if ($pageType != 'tag') {
	?>
	<div id="tagList">
	<?php
	if ($pageType == 'company') {
	?>
		<span>Industries:</span>
		<ul id="industryTags" companyid="<?=$companyInfo['CompanyID']?>">
			<?php
			foreach ($companyTags AS $tag) {
			?>
					<li<?php if($tag['uservoted']) { ?> class="industryUserVoted" <?php } ?>>
						<span class="industryName"><?=$tag['Name']?></span>
						<span>(</span>
							<span class="industryTotal"><?=$tag['votes']?></span>
						<span>)</span>
						<span class="industryUpvote" tagid="<?=$tag['TagsID']?>" companyid="<?=$companyInfo['CompanyID']?>" voted="<?=$tag['uservoted']?>">
							<?php if($tag['uservoted']) { ?>
									-
							<?php }else { ?>
									+
							<?php } ?>
						</span>
					</li>
			<?php } ?>
		</ul>
		<a id="addIndustry" href="#">add industry</a>
		<div id="newIndustryPopup" style="display:none;">
			<input id="newindustry_name" type="text" placeholder="Type an Industry name"/>
		</div>	
	<?php
	} else {
	?>
		<span>Tags:</span>
		<span>Industries:</span>
		<ul id="claimTags" claimid="<?=$claimInfo['ClaimID']?>">
			<?php
			foreach ($claimTags AS $tag) {
			?>
				<li <?php if($tag['uservoted']) { ?> class="claimTagUserVoted" <?php } ?> >
					<span class="claimTagName"><?=$tag['Name']?></span>
					<span>(</span>
						<span class="claimTagTotal"><?=$tag['votes']?></span>
					<span>)</span>
					<span class="claimTagUpvote" tagid="<?=$tag['TagsID']?>" companyid="<?=$claimInfo['CompanyID']?>" voted="<?=$tag['uservoted']?>">
						<?php if($tag['uservoted']) { ?>
								-
						<?php }else { ?>
								+
						<?php } ?>
					</span>
				</li>
			<?php } ?>
		</ul>
		<a id="addClaimTag" href="#">add claim tag</a>
		<div id="newClaimTagPopup" style="display:none;">
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