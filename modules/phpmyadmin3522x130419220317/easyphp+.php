<?php
$module_ini = array();
$module_ini = array(
	"module_name" 		=> array(
		"en"	=>	"MySQL Administration : PhpMyAdmin",
		"fr"	=>	"Administration MySQL : PhpMyAdmin"
		),
	"module_version" 	=> "3.5.2.2",
	"module_date" 		=> "2013-04-19 22:03:17",
	"en"	=>	array(
		"Application"	=>	array(
				"Name"		=>	"PhpMyAdmin",
				"Version"	=>	"3.5.2.2",
				"Website"	=>	"<a href='http://www.phpmyadmin.net/' target='_blank'>www.phpmyadmin.net</a>"
		),
		"Installation Parameters"	=>	array(
				"Date"		=>	"2013-04-19 22:03:17",
		),
	),
	"fr"	=>	array(
		"Application"	=>	array(
				"Nom"		=>	"PhpMyAdmin",
				"Version"	=>	"3.5.2.2",
				"Site web"	=>	"<a href='http://www.phpmyadmin.net/' target='_blank'>www.phpmyadmin.net</a>"
		),
		"Param&egrave;tres d'installation"	=>	array(
				"Date"		=>	"2013-04-19 22:03:17",
		),
	),	
);

$module_i18n = array();
$module_i18n = array(
	"en"	=>	array(
		"info"	=>	"info",
		"open"	=>	"open"
	),
	"fr"	=>	array(
		"info"	=>	"info",	
		"open"	=>	"ouvrir"
	),
);

echo "<div class='modules'>";

	/* -- MODULE -- */
	echo "<div class='module_name' style='width:400px'>";
	echo "<img src='images_easyphp/module.gif' width='16' height='11' alt='module' />" . $module_ini['module_name'][$lang] . " " . $module_ini['module_version'];
	echo "<span>" . $module_ini['module_date'] . "</span>";
	echo "</div>";
	/* ---------- */

	
	/* -- LINKS -- */
	echo "<div class='module_link'><a href='../modules/" . $file . "' target='_blank'>" . $module_i18n[$lang]['open'] . "</a></div>";
	/* ---------- */


	/* -- INFO -- */
	echo "<div class='module_parameters'><a href='index.php?to=" . $file . "#anchor_".$file."' name='anchor_".$file."'><img src='images_easyphp/plus.gif' width='12' height='9' alt='+' border='0' />" . $module_i18n[$lang]['info'] . "</a></div>";
	echo "<br style='clear:both' />";
	if ($_GET['to'] == $file) { 
		echo "<div class='module_parameters_frame'>";
			echo "<div class='close'><a href='index.php'>$close</a></div>";
			foreach($module_ini[$lang] as $section_name => $section) {
				if ($section_name != "Links") {
					echo "<div class='module_ini_section'>" . $section_name . "</div>";
					foreach($section as $title => $text) {
						echo "<div class='module_ini_settings'><span>" . $title . "</span> : " . $text . "</div>";
					}
				}
			}
			echo "<div class='module_ini_section'>" . $module_uninstall . "</div>";
			echo "<div class='module_ini_settings'>" . $module_uninstall_folder . "</div>";
			echo "<div class='module_ini_settings' style='text-align:center;color:#BFB800;'><pre>" . $easyphp_path . "modules\\" . $file . "</pre></div>";
		echo "</div>";
	}
	/* ---------- */

echo "</div>";
?>
