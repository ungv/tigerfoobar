<?php
// If the user has not submitted anything before, display message
if ($pageType == 'profile' && empty($listofclaims[0]['ClaimID'])) { ?>
	<p style="margin-bottom: 20px;">You haven't submitted any content yet! Get started with the button at the top!</p>
	<style type="text/css">
		#treemapFilters, #treemapIncrementDecrement, #treemapCanvas, #toggleviewContainer {
			display: none;
		}
	</style>
<?php
	return false;
}

if ($pageType == 'profile' && count($listofclaims) < 5) { ?>
	<div id="listview">
<?php } else { ?>
	<div id="listview" class="scrollBox">
<?php } ?>

		<ul id="claimsList">
			<?php
			foreach ($listofclaims as $claim) {
			?>
			<li id="<?=$claim['Score']?>">
				<p><a href="<?=base_url()?>claim/<?=$claim['ClaimID']?>"><?=$claim['Title']?></a></p>
				<p style="float: right;">Linked to <a href="<?=base_url()?>company/<?=$claim['CompanyID']?>"><?=$claim['CoName']?></a></p>
			</li>
			<?php
			}
		?>
		</ul>
	</div>