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
 
// Export old php.ini
copy('../conf_files/php.ini', '../php/' . $_GET['oldphpdir'] . '/php.ini');	

// Import new php.ini
copy('../php/' . $_GET['newphpdir'] . '/php.ini', '../conf_files/php.ini');	

$redirect = "http://" . $_SERVER['HTTP_HOST'] . "/home/index.php?page=php-page&display=changephpversion";
sleep(1);
header("Location: " . $redirect); 
exit;
?>