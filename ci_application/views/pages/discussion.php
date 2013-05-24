	<!--Start Discussion content-->
	<div id="discussionContainer" class="container">
		<h2>Discussion</h2>
		<div id="discussionContent" class="content">
			<div>
				<span><?=count($comments) . (count($comments) == 1 ? ' comment, ' : ' comments, ') . '[number of unique users]'?></span>
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
				<button class="submitButton submitReply" value="">Submit</button>
				<button class="cancelButton cancelReply" value="">cancel</button>				
			</div>
			<ul>
			<?php 
			if (empty($comments)) {
			?>
				No one has commented on this claim yet. Start a conversation with the button on the right!
			<?php
			}
			foreach ($comments as $comment) {
			?>
				<li id="comment<?=$comment['CommentID']?>" value="<?=$comment['Value']?>" style="left: <?=$comment['level'] * 15?>px; width: <?=900-$comment['level'] * 15?>px;">
					<h4>By: <?=$comment['Name']?> On: <?=$comment['Time']?></h4>
					<p><?=$comment['Comment']?></p>
					<div class="buttonsContainer" style="opacity: 0.4;">
						<p>(+<span class="upNum"><?=$comment['Ups']?></span> | -<span class="downNum"><?=$comment['Downs']?></span>)</p>
						<input type="radio" id="<?=$comment['CommentID']?>upvote" name="commentVoting" >
						<label for="<?=$comment['CommentID']?>upvote" class="buttons upVote <?= $comment['userVotedUp'] ? 'selectedVote' : '' ?>" voted="<?=$comment['userVotedUp']?>" claimID="<?=$comment['ClaimID']?>" value="1">&#9650;</label>
						<input type="radio" id="<?=$comment['CommentID']?>downvote" name="commentVoting">
						<label for="<?=$comment['CommentID']?>downvote" class="buttons downVote <?= $comment['userVotedDown'] ? 'selectedVote' : '' ?>" voted="<?=$comment['userVotedDown']?>" claimID="<?=$comment['ClaimID']?>" value="0">&#9660;</label>
						<button class="buttons reply" value="">Reply</button>
					</div>
				</li>
				<li id="comment<?=$comment['CommentID']?>reply" class="replyBox" style="display: none; left: <?=($comment['level']+1) * 15?>px; margin-right: <?=($comment['level']+1) * 15?>px">
					<textarea cols="100%" placeholder="Reply to this comment"></textarea>
					<button class="submitButton submitReply" value="">Submit</button>
					<button class="cancelButton cancelReply" value="">cancel</button>
				</li>
			<?php
			}
			?>
			</ul>
		</div>
	</div>
	<!--End Discussion content-->