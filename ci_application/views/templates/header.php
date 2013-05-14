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
			<?php if($isLogged) { ?>
				<span id="login_buttons" style="display: none;"><a id="login" href = "#"> Log In</a> | <a id="signup" href = "#">Sign Up</a></span>
				<span id="logout"><a href="action/logout">logout</a></span>
				<span id="login_status">Logged in as <?= $username ?></span>
				
			<?php }else { ?>
				<span id="login_buttons"><a id="login" href = "#">Log In</a> | <a id="signup" href = "#">Sign Up</a></span>
				<span id="logout" style="display: none;"><a href="action/logout">logout</a></span>
				<span id="login_status" style="display: none;"></span>
			<?php } ?>
		</SECTION>
	</header>

	<div id="loginPopup" style="display:none;">
		<h3>Login to your Account</h3>
		<p id="login_fail" style="display: none;">Login failed, Plese try again.</p>
		<input id="login_username" type="text" placeholder="Username"/>
		<input id="login_password" type="password" placeholder="Password"/>
		<input id="login_submit" type="submit" value="Log in"/>
		<input id="login_cancel" type="submit" value="Cancel"/>
	</div>

	<div id="signupPopup" style="display: none;">
		<form action="" method="post">
			<h3>Create an account and start helping others!</h3>
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