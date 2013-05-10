<div id="main">
	<div id="title">
		<h1>Apple plans to build $5 billion new headquarters in Cupertino</h1>
		<div id="user">Submitted 2 months ago by <a href="profile">thisDude</a></div>
		<div id="url">URL: <a href="http://www.google.com">Google</a></div>
		<div id="synopsis">Synopsis: Apple’s massive new spaceship-style headquarters is not on-budget or on schedule, according to a new report at Bloomberg...</div>
		<div id="tags">Tags:
			<?php
				// loop through tags table to display them in colored divs
			?>
		</div>
	</div>
	
	<div class="container">
		<h2>Score -</h2>
		<div id="scoreContent" class="expanded">
			<section id='scoreMap'>
				<!-- have to make form auto submit with js -->
				<div id='scoreDistrubution'>
<?php
				for ($i=0; $i <=6 ; $i++) { 
?>
					<p style='top: <?=7	*$i?>px'></p>
<?php
				}
?>	
				</div>

				<div id="scoreControl">
<?php
				$kudosColors = array('n3', 'n2', 'n1', 'zero', 'p1', 'p2', 'p3');
				for ($i=0; $i <=6 ; $i++) { 
?>
					<input type='radio' id="radio<?=$i?>" name='score' value='<?=$i?>'><label class="<?=$kudosColors[$i]?>" for="radio<?=$i?>"> </label>
<?php
				}
?>					
				</div>

				
			</section>

			<div id="linkedTo">
				
			</div>
		</div>
	</div>
	
	<div class="container">
		<h2>Discussion -</h2>
		<div id="discussionContent" class="expanded">
			<div id="rating">+3</div>
		</div>
	</div>
</div>