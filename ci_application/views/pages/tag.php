<?php
if ($pageType == 'profile' && count($userComments) < 5) {
?>
	<div id="listview">
<?php
} else {
?>
	<div id="listview" class="scrollBox">
<?php
}

		// If the user has not submitted anything before, display message
		if ($pageType == 'profile' && empty($listofclaims[0]['ClaimID'])) {
		?>
			<p>You haven't submitted any content yet! Get started with the button at the top!</p>
		<?php
		} else {
		?>
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
		}
		?>
		</ul>
	</div>