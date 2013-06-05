	<!--Start Discussion content-->
	<div id="discussionContainer" class="container" claimid="<?=$claimID?>">
		<h2>Discussion</h2>
		<div id="discussionContent" class="content">
			<div>
				<span><?=count($comments) . (count($comments) == 1 ? ' comment' : ' comments') . ', between ' . $uniqueUsers . ' people'?></span>
				<button type="button" id="newComment">Start a new thread</button>
				<!-- <div id="newCommentPopup" class="popup" style="display: none;">
					<h4>Add a new comment!</h4>
					<textarea placeholder="Your comments on this article"></textarea>
					<button type="submit" class="submitButton">Submit</button>
					<button type="button" class="cancelButton">cancel</button>
				</div> -->
			</div>
			<div id="newCommentBox" style="display: none;">
				<textarea cols="100%" placeholder="Your comments on this article"></textarea>
				<button id="newThread" class="submitButton submitReply" value="">Submit</button>
				<button class="cancelButton cancelReply" value="">cancel</button>				
			</div>
			<?php 
			if (empty($comments)) {
			?>
				<ul style="padding: 5px;">
				No one has commented on this claim yet. Start a conversation with the button on the right!
			<?php
			} else {
				?>
				<ul>
				<?php
				foreach ($comments as $comment) {
				?>
					<li id="<?=$comment['CommentID']?>comment" value="<?=$comment['Value']?>" style="left: <?=$comment['level'] * 15?>px; width: <?=900-$comment['level'] * 15?>px;" level="<?=$comment['level']?>" class='comments' title='<?=$comment['Name']?> rated this claim as <strong><?=$comment['Value']?></strong>'>
						<?php
							// Calculate time past since comment posted
							// PHP time() is in Europe/Paris (+9) timezone, but MySQL server time is UTC (+3),
							// So need to subtract 6 hours to adjust
							$timesince = date(time() - strtotime($comment['Time'])) - 21600;
							$identifier = 'seconds ago';
							if ($timesince >= 60) {
								$timesince = $timesince / 60;
								$identifier = 'minute' . ($timesince > 2 ? 's' : '') . ' ago';
								if ($timesince >= 60) {
									$timesince = $timesince / 60;
									$identifier = 'hour' . ($timesince > 2 ? 's' : '') . ' ago';
									if ($timesince >= 24) {
										$timesince = $timesince / 24;
										$identifier = 'day' . ($timesince > 2 ? 's' : '') . ' ago';
										if ($timesince >= 7) {
											$timesince = $timesince / 7;
											$identifier = 'week' . ($timesince > 2 ? 's' : '') . ' ago';
											if ($timesince >= 52) {
												$timesince = $timesince / 52;
												$identifier = 'year' . ($timesince > 2 ? 's' : '') . ' ago';
											}
										}
									}
								}
							}
						?>
						<h4>By: <?=$comment['Name']?> <em style="font-size: 9pt; color: darkgray;">(<?=floor($timesince) . ' ' . $identifier?>)</em></h4>
						<p><?=$comment['Comment']?></p>
						<div class="buttonsContainer" style="opacity: 0.4;">
							<p>(+<span class="upNum"><?=$comment['Ups']?></span> | -<span class="downNum"><?=$comment['Downs']?></span>)</p>
							<input type="radio" id="<?=$comment['CommentID']?>upvote" name="commentVoting" >
							<label for="<?=$comment['CommentID']?>upvote" class="buttons upVote <?= $comment['userVotedUp'] ? 'selectedVote' : '' ?>" voted="<?=$comment['userVotedUp']?>" value="1">&#9650;</label>
							<input type="radio" id="<?=$comment['CommentID']?>downvote" name="commentVoting">
							<label for="<?=$comment['CommentID']?>downvote" class="buttons downVote <?= $comment['userVotedDown'] ? 'selectedVote' : '' ?>" voted="<?=$comment['userVotedDown']?>" value="0">&#9660;</label>
							<img class='flagComment' commentID="<?=$comment['CommentID']?>" src="<?=base_url()?>img/flag.png" title='Flag Comment'>
							<button class="buttons reply" value="">Reply</button>
						</div>
					</li>
					<li id="<?=$comment['CommentID']?>commentreply" class="replyBox" style="display: none; left: <?=($comment['level']+1) * 15?>px; margin-right: <?=($comment['level']+1) * 15?>px">
						<textarea cols="100%" placeholder="Reply to this comment"></textarea>
						<button class="submitButton submitReply" value="">Submit</button>
						<button class="cancelButton cancelReply" value="">cancel</button>
					</li>
				<?php
				}
			}
			?>
			</ul>
		</div>
	</div>
	<!--End Discussion content-->