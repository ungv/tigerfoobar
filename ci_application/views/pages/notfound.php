<div>
	<?php
	if ($pageType == 'claim') {
		?>
		Sorry, this claim could not be found. Try going to the <a href="/claim/">claims page</a>
		<?php
	} else if ($pageType == 'company') {
		?>
		Sorry, this company could not be found. Try going to the <a href="/company/">companies page</a>
		<?php		
	} else if ($pageType == 'tag') {
		?>
		Sorry, this tag could not be found. Try going to the <a href="/tag/">tags page</a>
		<?php
	} else if ($pageType == 'profile') {
		?>
		Sorry, this profile could not be found. Try going to the <a href="/profile/">profiles page</a>
		<?php
	}
	?>
</div>