<?php 
	if (isset($tagInfo[0])) {
	?>
		<div>
			<h1>
				#<?=$tagInfo[0]['Name']?>
			</h1>
		</div>
		
		<div><h4><em>Claims or companies associated with this tag:</em></h4></div>
	<?php
	} else {
	?>
		<div>
			<h1>
				Tag not found
			</h1>
		</div>
	<?php
	}
?>