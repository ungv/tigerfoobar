<!--Start #main content-->
<div id="main">
	<h1>
	<?php
	
	if ($pageType == 'company') {
		echo ($companyInfo[0]['Name']);
	} else if (isset($claimInfo[0]['ClaimTitle'])) {
		echo ($claimInfo[0]['ClaimTitle']);
	} else if (isset($tagInfo[0]['Name'])) {
		echo ('"' . $tagInfo[0]['Name'] . '"');
	}
	
	?>
	</h1>
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