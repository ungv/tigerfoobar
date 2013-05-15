<!--Start Score content-->
	<div id="controlContainer">
		<div id='scoreDistribution'>
		<?php
		for ($i=0; $i <=6 ; $i++) { 
			?>
			<div style='top: <?=7	*$i?>px'>
			<?php
			for ($j=0; $j < 4; $j++) { 
				?>
				<p class='circle'></p>
				<?php
			}
			?>	
			</div>
			<?php
		}
		?>	
		</div>

		<div id="scoreControl">
		<?php
		for ($i=0; $i <=6 ; $i++) { 
			?>
			<input type='radio' id="radio<?=$i?>" name='score' value='<?=$i-3?>'>
			<label class="scoreBox" for="radio<?=$i?>" value="<?=$i-3?>"></label>
			<?php
		}
		?>
		</div>
	</div>
<!--End Score content-->