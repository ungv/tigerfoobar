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
			<!-- <div id="rating">+3</div> -->
			<ul>
			<?php 
			// function printComments() {
				foreach ($comments as $comment) {
				?>
					<li style='border-color: #<?=mt_rand(100000, 999999)?>; left: <?=$comment['level'] * 20?>px;'>
						<h4>By: <?=$comment['Name']?> On: <?=$comment['Time']?></h4>
						<p><?=$comment['Comment']?></p>
					</li>
				<?php
				}
			// }
			?>
			</ul>
		</div>
	</div>
	<!--End Discussion content-->