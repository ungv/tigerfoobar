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
 
include('../php/' . $_POST['phpdir'] . '/easyphp.php');

$newnotes = '<?php
$phpversion = array();
$phpversion = array(
	"status"	=> "' . $phpversion['status'] . '",
	"dirname"	=> "' . $phpversion['dirname'] . '",
	"name" 		=> "' . $phpversion['name'] . '",
	"version" 	=> "' . $phpversion['version'] . '",
	"date" 		=> "' . $phpversion['date'] . '",
	"notes"		=> "' . htmlspecialchars($_POST['version_notes']) . '",
);
?>';
file_put_contents('../php/' . $_POST['phpdir'] . '/easyphp.php', $newnotes);

$redirect = "http://" . $_SERVER['HTTP_HOST'] . "/home/index.php?page=php-page&display=changephpversion";
header("Location: " . $redirect); 
exit;
?>