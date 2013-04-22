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
	$source = "../conf_files/my.ini";
	$myini = file_get_contents($source);

	// Backup old php.ini
	copy('../conf_files/my.ini', '../conf_files/my_' . date("Y-m-d@U") . '.ini');

	foreach ($_POST as $parameter => $parameter_value){
		// Search and replace
		$pattern = "/" . $parameter . "(\s|\t|)=(\s|\t|)(.*)/";		
		$replacement = $parameter . " = " . $parameter_value;
		$myini = preg_replace($pattern, $replacement, $myini);
	}

	// Save new php.ini
	file_put_contents('../conf_files/my.ini', $myini);

	$redirect = "http://" . $_SERVER['HTTP_HOST'] . "/home/index.php?page=database-page";
	sleep(2);
	header("Location: " . $redirect); 
	exit;
}
?>