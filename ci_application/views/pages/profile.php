	
	<!-- User submissions -->
	<div id="listview">
		<div id="submissions">
			<?php
			// If the user has not submitted anything before, display message
			if (empty($userClaims[0]['ClaimID'])) {
			?>
				<p>You haven't submitted any content yet! Get started with the button at the top!</p>
			<?php
			} else {
			?>
			<ul>
				<?php
				foreach ($userClaims as $claim) {
				?>
				<li id="<?=$claim['Score']?>"><a href="/claim/<?=$claim['ClaimID']?>"><?=$claim['Title']?></a></li>
				<?php
				}
			}
			?>
			</ul>
		</div>
	</div>
	
	<!-- User comments -->
	<div id="comments" class="container">
		<h3>Comments:</h3>
		<div class="scrollBox">
			<?php
			// If the user has not commented on anything before, display message
			if (empty($userComments[0]['Comment'])) {
			?>
				<p>You haven't commented on anyone's claims yet! Get started on the <a href="/claim">claims page</a></p>
			<?php
			} else {
				foreach ($userComments as $comment) {
				?>
			<div class="threadContainer">
				<div class="thread">
					<h3><a href="/claim/<?=$comment['ClaimID']?>/#<?=$comment['CommentID']?>comment">"<?=$comment['Comment']?>"</a><span style="font-size: 10pt;"> on <?= date("F j, Y, g:i a", strtotime($comment['Time'])-10800)?></span></h3>
					<p><em>About <a href="/company/<?=$comment['CompanyID']?>"><?=$comment['CoName']?></a>, from '<a href="/claim/<?=$comment['ClaimID']?>"><?=$comment['Title']?></a>,' of which they <?=$comment['Value'] == NULL ? 'have not rated yet' : 'gave a score of <strong class="theirRating">' . $comment["Value"] .'</strong>'?> </em>
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
				<p>You haven't voted on anyone's comments yet! Get started on the <a href="/claim">claims page</a></p>
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
						"<a href="/claim/<?=$vote['ClaimID']?>/#<?=$vote['CommentID']?>comment"><?=$vote['Comment']?></a>"
					</p>
					<p class="coComment">about <a href="/company/<?=$vote['CompanyID']?>"><?=$vote['CoName']?></a></p>
				</li>
			<?php
				}
			}
			?>
		</div>
	</div>
