	<!--Start Discussion content-->
	<div id="discussionContainer" class="container">
		<h2>Discussion</h2>
		<div id="discussionContent" class="content">
			<!-- <div id="rating">+3</div> -->
			<ul>
			<?php 
			for ($i=0; $i < 6; $i++) { 
			?>
				<li style='border-color: #<?=mt_rand(100000, 999999)?>; left: <?=5*$i?>px;'>
					<h4>By: Scott On: 4/25/2013</h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam facilisis scelerisque leo, ut consectetur eros rutrum a. Phasellus vel arcu lectus. Nullam cursus eleifend mattis. Aliquam erat volutpat. Donec bibendum, augue a eleifend hendrerit, turpis purus pharetra risus, nec consequat sapien nulla non metus. In hac habitasse platea dictumst. Curabitur quis lacus quis tortor interdum condimentum. Donec purus augue, molestie ac commodo a, hendrerit eu lacus. Aliquam erat volutpat. Quisque tristique arcu a arcu fringilla at dictum sapien bibendum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec facilisis massa eu dolor pharetra in rutrum purus tincidunt. Fusce vestibulum, massa ac venenatis scelerisque, metus purus ultricies massa, quis egestas neque sem consequat dui. Donec rhoncus scelerisque orci in ullamcorper.</p>
					<div class='discussionOptions' style='margin-right: <?=5*$i?>px;'>(+12|-5) Up Down Flag Reply</div>
				</li>
			<?php
			}
			?>
			</ul>
		</div>
	</div>
	<!--End Discussion content-->