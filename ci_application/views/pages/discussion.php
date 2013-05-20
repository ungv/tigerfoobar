	<!--Start Discussion content-->
	<div id="discussionContainer" class="container">
		<h2>Discussion</h2>
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