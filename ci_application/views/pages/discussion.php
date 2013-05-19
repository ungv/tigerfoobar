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
				<li value="<?=$comment['Value']?>" style='left: <?=$comment['level'] * 20?>px;'>
					<h4>By: <?=$comment['Name']?> On: <?=$comment['Time']?></h4>
					<p><?=$comment['Comment']?></p>
				</li>
			<?php
			}
			?>
			</ul>
		</div>
	</div>
	<!--End Discussion content-->