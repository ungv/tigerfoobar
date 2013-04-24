<!DOCTYPE html>
<head>
	<title><?= $headerTitle ?></title>

	<?php 
		/*Stylesheets*/
		/*include url helper for this to work*/
		echo base_url() . "css/index.css";
	?>
	<link href="<?= base_url() . "css/index.css" ?>" type="text/css" rel="stylesheet" />

	<?php /*Scripts*/?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js" type="text/javascript"></script>
	<script type = "text/javascript" src = "js/scripts.js"></script>
</head>
<body>
	<header>
		<!--Search box-->
		<input type="text" placeholder="Search for companies or products/services"/>
		
		<!--Profile/login/register-->
		<a href = "login.php">
			Me
		</a>
	</header>
	