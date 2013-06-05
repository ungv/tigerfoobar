<div>
	<?php
	if ($pageType == 'claimBrowse') {
		?>
		Sorry, this claim could not be found. But look at these top claims instead!
		<?php
	} else if ($pageType == 'companyBrowse') {
		?>
		Sorry, this company could not be found. But look at these top companies instead!
		<?php		
	} else if ($pageType == 'tag') {
		?>
		Sorry, this tag could not be found. But look at these top tags instead!
		<?php
	} else if ($pageType == 'profile') {
		?>
		Sorry, this profile could not be found.
		<?php
	}
	?>
</div>