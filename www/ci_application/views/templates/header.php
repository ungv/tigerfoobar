<!DOCTYPE html>
<head>
	<title><?= $headerTitle ?></title>

	<?php 
		/*Stylesheets*/
		/*include url helper for this to work*/
	?>
	<link href="<?= base_url() . "css/index.css" ?>" type="text/css" rel="stylesheet" />

	<?php /*Scripts*/?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js" type="text/javascript"></script>
</head>
<body>
	<header>
		<h1><?= $pageTitle ?></h1>
	</header>
	