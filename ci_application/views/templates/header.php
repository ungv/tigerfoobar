<!DOCTYPE html>
<head>
	<title><?= $headerTitle ?></title>

	<?php 
	/*Our Stylesheets*/
	foreach($csFiles as $csFile) { ?>
		<link href="<?= base_url() . 'css/' . $csFile . '.css' ?>" type="text/css" rel="stylesheet" />
	<?php } ?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="/css/tagit-awesome-blue.css">

	<?php /*jQuery Scripts*/?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js" type="text/javascript"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" type="text/javascript"></script>
	<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
	<script src="/js/additional-methods.min.js" type="text/javascript"></script>
</head>

<body>
	<header>
		<SECTION id='topbar'>
			<h1><a href="/">Patchwork</a></h1>
			<!--Search box-->
			<input id="tags" type="text" placeholder="Search for companies or products/services"/>
			<!--Profile/login/register-->
			<?php if($isLogged) { ?>
				<span id="login_buttons" style="display: none;">
					<a id="login" href = "#"> Log In</a> | <a id="signup" href = "#">Sign Up</a>
				</span>
				<span id="logout"><a href="/action/logout">logout</a></span>
				<span id="login_status">Logged in as <a href="/profile/<?=$userid?>"><?= $username ?></a></span>
				
			<?php }else { ?>
				<span id="login_buttons"><a id="login" href = "#">Log In</a> | <a id="signup" href = "#">Sign Up</a></span>
				<span id="logout" style="display: none;"><a href="/action/logout">logout</a></span>
				<span id="login_status" style="display: none;"></span>
			<?php } ?>
		</SECTION>
	</header>

	<div id="loginPopup" class="popup" style="display:none;">
		<h3>Login to your Account</h3>
		<p id="login_fail" style="display: none;">Login failed, Plese try again.</p>
		<input id="login_username" type="text" placeholder="Your codename"/>
		<input id="login_password" type="password" placeholder="Your top secret password"/>
		<button type="submit" id="login_submit" class="submitButton">Log in</button>
		<button type="button" id="login_cancel" class="cancelButton">cancel</button>
	</div>

	<form id="signupForm">
		<div id="signupPopup" class="popup" style="display: none;">
			<h3>Create an account and start helping others!</h3>
			<input type="text" name="username" placeholder="Desired codename"/>
			<input type="password" name="password" placeholder="Top secret password"/>
			<input type="text" name="email" placeholder="Email (*optional)"/>
			<p>
				*We only require an email to help you recover a lost username/password and will never spam you with anything, ever!
			</p>
			<button type="submit" id="signup_submit" class="submitButton">Sign me up!</button>
			<button type="button" id="signup_cancel" class="cancelButton">Wait, no...</button>
		</div>
	</form>