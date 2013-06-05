<div>
	<?php
	if ($pageType == 'claim') {
		?>
		Sorry, this claim could not be found. Try going to the <a href="<?=base_url()?>claim/">claims page</a>
		<?php
	} else if ($pageType == 'company') {
		?>
		Sorry, this company could not be found. Try going to the <a href="<?=base_url()?>company/">companies page</a>
		<?php		
	} else if ($pageType == 'tag') {
		?>
		Sorry, this tag could not be found. Try going to the <a href="<?=base_url()?>tag/">tags page</a>
		<?php
	} else if ($pageType == 'profile') {
		?>
		Sorry, this profile could not be found. Try going to the <a href="<?=base_url()?>profile/">profiles page</a>
		<?php
	}
	?>
</div>