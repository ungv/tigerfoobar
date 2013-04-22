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
 
// Backup old php.ini
copy('../conf_files/php.ini', '../conf_files/php_' . $_GET['oldphpdir'] . '_' . date("Y-m-d@U") . '.ini');

// Update old easyphp.php
$oldeasyphp = file_get_contents('../php/' . $_GET['oldphpdir'] . '/easyphp.php');
$oldeasyphp = str_replace('"status"	=> "1"', '"status"	=> "0"', $oldeasyphp);
file_put_contents('../php/' . $_GET['oldphpdir'] . '/easyphp.php', $oldeasyphp);

// Update new easyphp.php
$neweasyphp = file_get_contents('../php/' . $_GET['newphpdir'] . '/easyphp.php');
$neweasyphp = str_replace('"status"	=> "0"', '"status"	=> "1"', $neweasyphp);
file_put_contents('../php/' . $_GET['newphpdir'] . '/easyphp.php', $neweasyphp);

// Import new php.ini
$phpini_apache = file_get_contents('../php/' . $_GET['newphpdir'] . '/php.ini');
$phpini_apache = str_replace('${path}', dirname(dirname(__FILE__)), $phpini_apache);
file_put_contents('../apache/php.ini', $phpini_apache);

// Update httpd.conf
$httpdconf = file_get_contents('../conf_files/httpd.conf');

	// Backup old httpd.conf
	copy('../conf_files/httpd.conf', '../conf_files/httpd_' . date("Y-m-d@U") . '.conf');
	
	// Search and replace
	$pattern = "/" . 'LoadModule php5_module' . "+(.*)/";
	$replacement = 'LoadModule php5_module "${path}/php/' . $_GET['newphpdir'] . '/php5apache2_4.dll';
	$httpdconf = preg_replace($pattern, $replacement, $httpdconf);
	
	// Save new httpd.conf
	file_put_contents('../conf_files/httpd.conf', $httpdconf);

$redirect = "http://" . $_SERVER['HTTP_HOST'] . "/home/change_php_update_step2.php?newphpdir=" . $_GET['newphpdir'] . "&oldphpdir=" . $_GET['oldphpdir'];
sleep(1);
header("Location: " . $redirect); 
exit;
?>