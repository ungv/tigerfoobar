<?php
	//date_default_timezone_set('America/Los_Angeles');
	//echo date_default_timezone_get();
?>
<!DOCTYPE html>
<head>
	<title><?= $headerTitle ?></title>

	<link rel="icon" type="<?=base_url()?>image/png" href="/img/favicon.png" />

	<?php 
	/*Our Stylesheets*/
	foreach($csFiles as $csFile) { ?>
		<link href="<?= base_url() . 'css/' . $csFile . '.css' ?>" type="text/css" rel="stylesheet" />
	<?php } ?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="<?=base_url()?>css/tagit-awesome-blue.css">

	<?php /*jQuery Scripts*/?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js" type="text/javascript"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>js/jquery.validate.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>js/additional-methods.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>js/jquery.tooltipster.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>js/tagit.js" type="text/javascript"></script>	
	<script src="<?=base_url()?>js/highcharts.js" type="text/javascript"></script>	
</head>

<body>
	<header>
		<h1><a href="<?=base_url()?>"><img id="logoImage" src="<?=base_url()?>img/patchwork_logo.png"/><span id="title_text">atchwork</span></a></h1>
		<SECTION id='topbar'>
			<!--Search box-->
			<input id="searchInput" type="text" placeholder="Search for companies or products/services"/>
			<ul id="autoCompleteResults" style="display: none;">
				<ul id="claimResults">
					<h3>Claims</h3>
				</ul>
				<ul id="companyResults">
					<h3>Companies</h3>
				</ul>
			</ul>
		</SECTION>
		<!--Profile/login/register-->
		<?php if($isLogged) { ?>
			<span id="login_buttons">
				<a id="login_status" href="/profile/<?=$userid?>"><?= $username ?></a>
				<a id="urlButton" href="#">+</a>
				<a id="logout" href="/action/logout">logout</a>
			</span>
		<?php }else { ?>
			<span id="login_buttons">
				<a id="login" href = "#">Log In</a>
				<a id="signup" href = "#">Sign Up</a>
			</span>
		<?php } ?>
		<form id="loginForm" action="javascript:sendLogin()">
			<div id="loginPopup" class="popup" style="display:none;">
				<h3>Login to your Account</h3>
				<p id="login_fail" style="display: none; color: red;">Login failed, please try again.</p>
				<input id="login_username" type="text" placeholder="Your codename"/>
				<input id="login_password" type="password" placeholder="Your top secret password"/>
				<button type="submit" id="login_submit" class="submitButton">Log in</button>
				<button type="button" id="login_cancel" class="cancelButton">cancel</button>
			</div>
		</form>
		<form id="signupForm" action="javascript:addUser()">
			<div id="signupPopup" class="popup" style="display: none;">
				<h3>Create an account and start helping others!</h3>
				<p id="username_exists" style="display: none; color: red;">
					This username already exists, please try a different one.
				</p>
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
		<?/*
		
		*/?>
	
<?php
	// Navigation - Breadcrumbs
	// if claim
	if ($pageType == 'claim' && $claimInfo != null) {
?>
		<div id='navv'>
			<p>
				<a href="<?=base_url()?>claim">Claims</a> >> 
				<a href="<?=base_url()?>company/<?=$claimInfo['CompanyID']?>"><?=$claimInfo['CoName']?></a> >>
				<strong><?=$claimInfo['ClaimTitle']?></strong>
			</p>
		</div>

<?php
	} elseif ($pageType == 'company' && !empty($companyInfo)) {
?>
		<div id='navv'>
			<p>
				<a href="<?=base_url()?>company">Companies</a> >> 
				<strong><?=$companyInfo['Name']?></strong>
			</p>
		</div>

<?php		
	}
?>
			
	</header>

	<div class="lightsout"></div>
