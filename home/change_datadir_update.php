<?php
/**
 * EasyPHP : a complete WAMP environment for PHP developers 
 * including PHP, Apache, MySQL, PhpMyAdmin, Xdebug...
 *
 * PHP version 5
 *
 * @author   Laurent Abbal <laurent@abbal.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html  GPL License 2 
 * @link     http://www.easyphp.org
 */
 
include("i18n.inc.php");

$new_datadir = '';
if (isset($_GET['mode']) && ($_GET['mode'] == "portable")) $new_datadir = '../mysql/data';
if (isset($_POST['mode']) &&  $_POST['mode'] == "newdatadir") $new_datadir = $_POST['new_datadir'];

$new_datadir = $new_datadir . '/';
$new_datadir = str_replace("\\","/", $new_datadir);
$new_datadir = str_replace("//","/", $new_datadir);
					
$warning_message = '';
if ($new_datadir == "/") {
	$warning_message = $t_changemysqldatadir_warning_1;
} elseif (($new_datadir != "") && (!is_dir($new_datadir))) {
	$warning_message = $t_changemysqldatadir_warning_2;
} elseif (($new_datadir != "") && (is_dir($new_datadir))) {
	$list = scandir($new_datadir);
	$tests = array('mysql','performance_schema','phpmyadmin');
	$result = array_intersect($list, $tests);
	if (count($tests) != count($result)) {
		$warning_message = $t_changemysqldatadir_warning_3;
	}
}

if ($warning_message != '') {

	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<title>:-/</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="shortcut icon" href="images_easyphp/easyphp_favicon.ico" />
	<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
	<body>	
	<div class='menu_display'>
	<h5><?php echo $t_mysql_changedir; ?></h5>
	<div class='error_message_frame'>
		<div class='back'>
			<a href="javascript:history.back()" title="<?php echo $t_back; ?>">
			<img src="images_easyphp/back2.png" width="12" height="12" alt="<?php echo $t_back; ?>" title="<?php echo $t_back; ?>" border="0" />
			</a>
		</div>
		<div class='back_warning'>
			<span>
				<img src="images_easyphp/warning.png" width="12" height="12" alt="<?php echo $t_warning; ?>" title="<?php echo $t_warning; ?>" border="0" />
			</span>
		</div>
		<div class='error_message'><?php echo $warning_message; ?></div>
		<br style='clear:both;' />
	</div>
	<br style='clear:both;' />
	</div>
	</body>
	</html>
	<?php
	
} else {

	// Get my.ini content
	$myini = file_get_contents('../conf_files/my.ini');

	// Backup old my.ini
	copy('../conf_files/httpd.conf', '../conf_files/my_' . date("Y-m-d@U") . '.ini');
	
	// Search and replace
	$pattern = "/datadir(\s|\t|)=(\s|\t|)(.*)/";	
	if ($_GET['mode'] == "portable") $replacement = 'datadir="${path}/mysql/data/"';
	if ($_POST['mode'] == "newdatadir") $replacement = 'datadir="' . $new_datadir . '"';
	$myini = preg_replace($pattern, $replacement, $myini);

	// Save new my.ini
	file_put_contents('../conf_files/my.ini', $myini);

	$redirect = "http://" . $_SERVER['HTTP_HOST'] . "/home/index.php?page=database-page";
	sleep(1);
	header("Location: " . $redirect); 
	exit;
}
?>