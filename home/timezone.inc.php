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

$timezone = date_default_timezone_get();

if (isset($_POST['timezone']) AND $_POST['timezone'] != $timezone)
{
	$conf_path = "../conf_files/httpd.conf";
	$fp = fopen($conf_path, "r");
	$content_conf = fread($fp, filesize($conf_path));
	fclose($fp);
	$new_content_conf = str_replace($timezone, $_POST['timezone'], $content_conf);
	
	// Backup old httpd.conf
	$backup_apacheconf = "../conf_files/httpd_" . date("Y-m-d@U") . ".conf";
	$fp_backup = fopen($backup_apacheconf, 'w');
	fputs($fp_backup, $content_conf);
	fclose($fp_backup);			

	// Save new httpd.conf
	$fp = fopen($conf_path, "w");
	fputs($fp,$new_content_conf);
	fclose($fp);
	Header("Location: " . $_SERVER['PHP_SELF'] . "?to=timezones"); 
	exit;
}

if (isset($_POST['timezone']) AND $_POST['timezone'] == $timezone)
{
	Header("Location: " . $_SERVER['PHP_SELF']); 
	exit;
}


function timezones_select($selectedzone)
{
	echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
	echo '<select name="timezone">';
	function timezonechoice($selectedzone) {
		$all = timezone_identifiers_list();

		$i = 0;
		foreach($all AS $zone) {
			$zone = explode('/',$zone);
			$zonen[$i]['continent'] = isset($zone[0]) ? $zone[0] : '';
			$zonen[$i]['city'] = isset($zone[1]) ? $zone[1] : '';
			$zonen[$i]['subcity'] = isset($zone[2]) ? $zone[2] : '';
			$i++;
		}

		asort($zonen);
		$structure = '';
		foreach($zonen AS $zone) {
			extract($zone);
			if($continent == 'Africa' || $continent == 'America' || $continent == 'Antarctica' || $continent == 'Arctic' || $continent == 'Asia' || $continent == 'Atlantic' || $continent == 'Australia' || $continent == 'Europe' || $continent == 'Indian' || $continent == 'Pacific') {
				if(!isset($selectcontinent)) {
					$structure .= '<optgroup label="'.$continent.'">';
				} elseif($selectcontinent != $continent) {
					$structure .= '</optgroup><optgroup label="'.$continent.'">';
				}

				if(isset($city) != ''){
					if (!empty($subcity) != ''){
						$city = $city . '/'. $subcity;
					}
					$structure .= "<option ".((($continent.'/'.$city)==$selectedzone)?'selected="selected "':'')." value=\"".($continent.'/'.$city)."\">".str_replace('_',' ',$city)."</option>";
				} else {
					if (!empty($subcity) != ''){
						$city = $city . '/'. $subcity;
					}
					$structure .= "<option ".(($continent==$selectedzone)?'selected="selected "':'')." value=\"".$continent."\">".$continent."</option>";
				}

				$selectcontinent = $continent;
			}
		}
		$structure .= '</optgroup>';
		return $structure;
	}
	echo timezonechoice($selectedzone);
	echo '</select>';
	echo '<input type="image" src="images_easyphp/submit_select.gif" title="submit" border="0" width="15" height="15" alt="submit" />';
	echo '<span>';
	echo date('l jS \of F Y');
	echo '</span>';
	echo '</form>';
}
?>