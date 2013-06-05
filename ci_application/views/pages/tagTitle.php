<?php 
	if (!empty($tagName[0])) {
	?>
		<div>
			<h1>
				#<?=$tagName[0]['Name']?>
			</h1>
		</div>
		<?php
		if (!empty($listofclaims)) {
		?>
		<div><h4><em>Claims or companies associated with this tag:</em></h4></div>
		<?php
		} else {
		?>
		<div><h4><em>No claims or companies were found with this tag</em></h4></div>
		<?php
		}
	}
?>