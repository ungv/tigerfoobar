<!DOCTYPE html>
<head>
	<title><?= $headerTitle ?></title>

	<?php 
	/*Our Stylesheets*/
	foreach($csFiles as $csFile) { ?>
		<link href="<?= base_url() . 'css/' . $csFile . '.css' ?>" type="text/css" rel="stylesheet" />
	<?php } ?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">

	<?php /*jQuery Scripts*/?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js" type="text/javascript"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" type="text/javascript"></script>

	
</head>
<body>
	<header>
		<SECTION id='topbar'>
			<h1><a href="/">Patchwork</a></h1>
			<!--Search box-->
			<input id="tags" type="text" placeholder="Search for companies or products/services"/>
			<!--Profile/login/register-->
			<span><a id="login" href = "/profile/1"> Log In</a> | <a id="signup">Sign Up</a></span>
		</SECTION>
	</header>
	<div id="signupPopup" style="display: none;">
		<form action="" method="post">
			<h3>Create a new account!</h3>
			<input type="text" class="outfocus" name="username" placeholder="Your desired codename"/>
			<input type="text" class="outfocus" name="password1" placeholder="Super secret password"/>
			<input type="text" class="outfocus" name="password2" placeholder="Password again for verification"/>
			<input type="text" class="outfocus" name="email" placeholder="Email (*optional)"/>
			<p>
				*We only require an email to help you recover a lost username/password and will never spam you with anything, ever!
			</p>
			<input type="submit" class="submitButton" value="Log me in!"/><input type="button" class="cancelButton" value="Wait no..."/>
		</form>
	</div>