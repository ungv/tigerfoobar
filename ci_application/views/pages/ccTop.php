<!--Start #main content-->
<div id="main">
	<h1><?=$pageType == 'company' ? $companyInfo['Name'] : $claimInfo[0]['ClaimTitle']?></h1>
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
			<li><?=$tag['Name']?> +<?=$tag['votes']?></li>
	<?php
	}
	?>
		</ul>
	</div>
	<?php
} else { // Currently viewing 'tag' pages

}
?>
<!--End of Top-->