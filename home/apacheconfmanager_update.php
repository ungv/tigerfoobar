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

if ($_POST){

	$source = "../conf_files/httpd.conf";
	$httpdconf = file_get_contents($source);
	
	// Backup old httpd.conf
	$backup_httpdconf = "../conf_files/httpd_" . date("Y-m-d@U") . ".conf";
	$fp_backup = fopen($backup_httpdconf, 'w');
	fputs($fp_backup, $httpdconf);
	fclose($fp_backup);	
	
	// TIMEZONE
	$timezone = date_default_timezone_get();
	if (isset($_POST['timezone']) AND $_POST['timezone'] != $timezone)
	{
		// Search and replace
		$search = $timezone;
		$replace = $_POST['timezone'];
		$httpdconf = str_replace($search, $replace, $httpdconf);
	}
	
	// Search and replace
	$search = ':' . $_SERVER['SERVER_PORT'];
	$replace = ':' . $_POST['new_server_port'];
	$httpdconf = str_replace($search, $replace, $httpdconf);

	// Save new httpd.conf
	$fp_update = fopen('../conf_files/httpd.conf', 'w');
	fputs($fp_update, $httpdconf);
	fclose($fp_update);	

	$redirect = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_POST['new_server_port'] . "/home/index.php?page=server-page";
	sleep(2);
	header("Location: " . $redirect); 
	exit;
}
?>