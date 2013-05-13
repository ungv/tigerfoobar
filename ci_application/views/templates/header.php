<!DOCTYPE html>
<head>
	<title><?= $headerTitle ?></title>

	<?php 
	/*Our Stylesheets*/
	foreach($csFiles as $csFile) { ?>
		<link href="<?= base_url() . 'css/' . $csFile . '.css' ?>" type="text/css" rel="stylesheet" />
	<?php } ?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<style type="text/css">
	#tags-label {
		display: block;
		font-weight: bold;
		margin-bottom: 1em;
	}
	</style>

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
			<span><a id="login" href = "/profile"> Log In</a> | <a id="signup" href = "/profile">Sign Up</a></span>
		</SECTION>
		
		
		
	</header>
	