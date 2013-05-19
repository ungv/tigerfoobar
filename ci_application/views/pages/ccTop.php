<!--Start #main content-->
<div id="main">
	<h1><?=$pageType == 'company' ? $companyInfo[0]['Name'] : $claimInfo[0]['ClaimTitle']?></h1>
<?php
if ($pageType != 'tag') {
	?>
	<div id="claimTags">
	<?php
	if ($pageType == 'company') {
	?>
		<span>Industries:</span>	
	<?php
	} else {
	?>
		<span>Tags:</span>	
	<?php
	}
	?>
		<ul id="industryTags">
	<?php
	foreach (($pageType == 'company' ? $companyTags : $claimTags) AS $tag) {
	?>
			<li>
				<span class="industryTotal"><?=$tag['votes']?></span>
				<span class="industryName"><?=$tag['Name']?></span>
				<span class="industryUpvote" tagid="<?=$tag['TagsID']?>" companyid="<?=$companyInfo['CompanyID']?>" voted="0">+</span>
			</li>
	<?php
	}
	?>
		</ul>
		<!-- add new tag and search box go here -->
	</div>
	<?php
} else { // Currently viewing 'tag' pages

}
?>
<!--End of Top-->