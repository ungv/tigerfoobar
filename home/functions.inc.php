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


// Check if port is available
function check_port($port) {
	$conn = @fsockopen("127.0.0.1", $port, $errno, $errstr, 0.2);
	if ($conn) {
		fclose($conn);
		return true;
	}
}

 
function i18n_string($string){
	  $string = htmlspecialchars($string);
	  return $string;
}


function cut($text, $length) {
	if (strlen($text) <= $length) return $text;
	if (strlen($text) > $length) return substr($text, 0, $length-3) . '...';
}


function cutatspace($text, $length) {
	if (strlen($text) <= $length) return $text;
	$text = nl2br($text);
	$pos = strrpos(substr($text, 0, $length), " ");
	if (is_integer($pos) && $pos) return substr($text, 0, $pos) . '...';
	else return substr($text, 0, $length) . '...';
}

function timezones_select($selectedzone)
{
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
}
?>