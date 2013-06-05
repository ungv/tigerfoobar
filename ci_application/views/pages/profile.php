	
	<?php
	// User submissions come from tag.php
	?>
	
	<!-- User comments -->
	<div id="comments" class="container">
		<h3>Comments:</h3>
		<?php
		if (count($userComments) < 5) {
		?>
			<div>
		<?php
		} else {
		?>
			<div class="scrollBox">
		<?php
		}
		// If the user has not commented on anything before, display message
		if (empty($userComments[0]['Comment'])) {
		?>
			<p>You haven't commented on anyone's claims yet! Get started on the <a href="<?=base_url()?>claim">claims page</a></p>
		<?php
		} else {
			foreach ($userComments as $comment) {
			?>
			<div class="threadContainer">
				<div class="thread">
					<h3><a href="<?=base_url()?>claim/<?=$comment['ClaimID']?>/#<?=$comment['CommentID']?>comment">"<?=$comment['Comment']?>"</a><span style="font-size: 10pt;"> on <?= date("F j, Y, g:i a", strtotime($comment['Time'])-10800)?></span></h3>
					<p><em>About <a href="<?=base_url()?>company/<?=$comment['CompanyID']?>"><?=$comment['CoName']?></a>, from '<a href="<?=base_url()?>claim/<?=$comment['ClaimID']?>"><?=$comment['Title']?></a>,' of which they <?=$comment['Value'] == NULL ? 'have not rated yet' : 'gave a score of <strong class="theirRating">' . $comment["Value"] .'</strong>'?> </em>
					</p>
				</div>
			</div>
			<?php
			}
		}
		?>
		</div>
	</div>
	
	<!-- User votes on comments -->
	<div id="votes" class="container">
		<h3>Votes:</h3>
		<div class="scrollBox">
			<?php
			// If the user has not commented on anything before, display message
			if ($userVotes[0]['Value'] == NULL) {
			?>
				<p>You haven't voted on anyone's comments yet! Get started on the <a href="<?=base_url()?>claim">claims page</a></p>
			<?php
			} else {
			?>
			<ul>
			<?php
			foreach ($userVotes as $vote) {
			?>
				<li>
					<p>
						<strong><?=$vote['Name']?></strong> 
						<?= $vote['Value'] == 0 ? '<span class="disliked">disliked</span>' : '<span class="liked">liked</span>' ?> on <?= date("F j, Y, g:i a", strtotime($vote['Time'])-10800)?>
					</p>
					<p class="votedComment">
						"<a href="<?=base_url()?>claim/<?=$vote['ClaimID']?>/#<?=$vote['CommentID']?>comment"><?=$vote['Comment']?></a>"
					</p>
					<p class="coComment">about <a href="<?=base_url()?>company/<?=$vote['CompanyID']?>"><?=$vote['CoName']?></a></p>
				</li>
			<?php
				}
			}
			?>
		</div>
	</div>
