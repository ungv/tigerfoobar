	<!--Start Discussion content-->
	<div id="discussionContainer" class="container">
		<h2>Discussion</h2>
		<button type="button" id="newComment">Start a new thread</button>
		<div id="newCommentPopup" class="popup" style="display: none;">
			<h4>Add a new comment!</h4>
			<textarea placeholder="Your comments on this article"></textarea>
			<button type="submit" class="submitButton">Submit</button>
			<button type="button" class="cancelButton">cancel</button>
		</div>
		<div id="discussionContent" class="content">
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
						<button class="buttons cancelButton downVote" value="0">Down</button>
						<button class="buttons submitButton upVote" value="1">Up</button>
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