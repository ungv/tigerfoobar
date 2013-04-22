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
 
/*  test - alias directory still exits ?  */
read_alias();
$conf_test_alias = $httpd_1."#alias\n";
$x = 1;
$alias_update = FALSE;
while($x<=$nb_alias) {
	$alias_exp = explode("\"", $alias[$x]);
	$alias_dir = $alias_exp[3];
	if (file_exists($alias_dir)) {
		$conf_test_alias = $conf_test_alias.$alias[$x].$directory[$x];
	} else {
		$alias_update = TRUE;
	}
	$x++;
}
$conf_test_alias = $conf_test_alias."#alias".$httpd_3;
if ($alias_update) {
	echo "update";
	$fp_update = fopen($source, "w");
	fputs($fp_update,$conf_test_alias);
	fclose($fp_update);
	sleep(2);	
	Header("Location: " . $_SERVER['PHP_SELF']); 
	exit;
}


if ($_GET['to'] == "del_alias") {
	read_alias();
	$conf_del_alias = $httpd_1."#alias\n";
	$x = 1;
	while($x<=$nb_alias) {
		if ($x != $_GET['num_alias']) {
			$conf_del_alias = $conf_del_alias.$alias[$x].$directory[$x];
		}
		$x++;
	}
	$conf_del_alias = $conf_del_alias."#alias".$httpd_3;
	$fp_del = fopen($source, "w");
	fputs($fp_del,$conf_del_alias);
	fclose($fp_del);
	//sleep(2);	
	Header("Location: " . $_SERVER['PHP_SELF']); 
	exit;
}


if ($_POST['to'] == "add_alias_2") {
	/*  alias name tests  */
	read_alias();
	$name_test = TRUE;
	$inc = 1;
	while($inc <= $nb_alias) {
		$exp4 = explode("\"",$alias[$inc]);
		$exp5 = explode("/",$exp4[1]);
		$alias_name = $exp5[1];
		if ($_POST['alias_name'] == $alias_name) {
			$name_test = FALSE;
		}
		$inc++;
	}
	
	$banned = array();
	$banned = array_merge($www_files, array("alias", "directory", "home", "error", "icons", "manual", "module", "modules"));
	foreach ($banned as $value) {
		if (stristr($_POST['alias_name'], $value) != FALSE) {
			$name_test = FALSE;
		}
	}

	if (($_POST['alias_name'] != "") && ($_POST['alias_link'] != "") && (is_dir($_POST['alias_link'])) && ($name_test == TRUE)) {
		read_alias();
		$alias_link = str_replace("\\","/", $_POST['alias_link']);
		$alias_link = str_replace("//","/", $alias_link);
		
		if (substr($alias_link, -1) == "/"){$alias_link = substr($alias_link,0,strlen($alias_link)-1);}
		$new_alias = "Alias \"/";
		$new_alias .= $_POST['alias_name'];
		$new_alias .= "\" \"";
		$new_alias .= $alias_link;
		$new_alias .= "\"\n";
		$new_alias .= "<Directory \"$alias_link\">\n";
		$new_alias .= "Options FollowSymLinks Indexes\r\n";
		$new_alias .= "AllowOverride All\r\n";
		$new_alias .= "Order deny,allow\r\n";
		$new_alias .= "Allow from 127.0.0.1\r\n";
		$new_alias .= "Deny from all\r\n";
		$new_alias .= "Require all granted\r\n";			
		$new_alias .= "</Directory>\r\n";
		
		$new_apacheconf = $httpd_1."#alias".$httpd_2.$new_alias."#alias".$httpd_3;
		
		// Backup old httpd.conf
		$backup_apacheconf = "../conf_files/httpd_" . date("Y-m-d@U") . ".conf";
		$fp_backup = fopen($backup_apacheconf, 'w');
		fputs($fp_backup, $httpd);
		fclose($fp_backup);		
		
		// Save new httpd.conf
		$fp_new = fopen($source, "w");
		fputs($fp_new,$new_apacheconf);
		fclose($fp_new);
		sleep(2);			
		Header("Location: " . $_SERVER['PHP_SELF']); 
		exit;
	}			
}


function read_alias() {
	global $alias, $directory, $httpd, $httpd_1, $n, $httpd_2, $httpd_3, $nb_alias, $source;
	$source = "../conf_files/httpd.conf";
	$fp = fopen($source, "r");
	$httpd = "";
	while (!feof($fp)) {
		$conf = fgets($fp, 4096);
		$httpd = $httpd.$conf;
	}
	fclose($fp);
	$exp1 = explode("#alias",$httpd);
	$httpd_1 = $exp1[0];
	$httpd_2 = $exp1[1];
	$httpd_3 = $exp1[2];
	$exp2 = explode("Alias",$httpd_2);
			
	$n = 1;
	$nb_alias = count($exp2)-1;
	while($n<=$nb_alias) {
		$exp3 = explode("<Directory",$exp2[$n]);
		$alias[$n] = "Alias".$exp3[0];
		$directory[$n] = "<Directory".$exp3[1];
		$n++;
	}
}

function list_alias() {
	global $inc, $alias, $nb_alias, $exp4, $exp5, $alias_link, $nb_alias, $alias_name, $alias_add, $alias_delete, $confirm, $cancel, $wait, $menu_alias_add;
	$inc = 1;
	if ($nb_alias != 0) {
		while($inc <= $nb_alias) {
			$exp4 = explode("\"",$alias[$inc]);
			$exp5 = explode("/",$exp4[1]);
			$alias_link = $exp4[3];
			$alias_name = $exp5[1];
			$alias_link = str_replace("/","\\", $alias_link);
			echo "<div class='row'>";
			echo "<div class='alias_name'>";
			echo "<img src='images_easyphp/alias.gif' width='16' height='11' alt='alias' /><a href='../$alias_name/' target='_blank'>$alias_name</a>";
			echo "<span class='alias_path'>$alias_link\</span>";
			echo "</div>";
			if (isset($_GET['num_alias'])) {
				if (($_GET['to'] == "del_confirm") & ($_GET['num_alias'] == $inc)) {
					echo "<div class='alias_del_confirm_frame'>";
					echo "<a href='index.php?to=del_alias&amp;num_alias=$inc' class='alias_del_confirm'>$confirm</a><a href='index.php' class='alias_del_cancel'>$cancel</a>";
					echo "</div>";
				} else {
					echo "<div class='alias_del'><a href='index.php?to=del_confirm&amp;num_alias=$inc' title='$alias_delete'><img src='images_easyphp/delete.png' width='9' height='9' alt='delete alias' border='0' /></a></div>";
				}
			} else {
				echo "<div class='alias_del'><a href='index.php?to=del_confirm&amp;num_alias=$inc' title='$alias_delete'><img src='images_easyphp/delete.png' width='9' height='9' alt='delete alias' border='0' /></a></div>";
			}
			echo "<br style='clear:both' />";
			echo "</div>";

			$inc++;
		}
		
	} else {
		echo "<br style='clear:both' />";
	}
}	
?>