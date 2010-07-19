<?php

class Settings {

/*===============================
** Name: addsetting
** Adds a setting to the list
*/	public static function addsetting($name, $module, $description = 'none', $mode = 'hide', $setting = 'none', $options = 'none', $intoptions = '0', $access_level = MODERATOR, $help = '') {
		global $db;
		global $chatBot;
		$name = strtolower($name);

		if ($chatBot->existing_settings[$name] != true) {
			$db->query("INSERT INTO settings_<myname> (`name`, `module`, `mode`, `setting`, `options`, `intoptions`, `description`, `source`, `access_level`, `help`) VALUES ('$name', '$module', '$mode', '" . str_replace("'", "''", $setting) . "', '$options', '$intoptions', '" . str_replace("'", "''", $description) . "', 'db', $access_level, '$help')");
		  	$chatBot->settings[$name] = $setting;
	  	} else {
			$db->query("UPDATE settings_<myname> SET `module` = '$module', `mode` = '$mode', `options` = '$options', `intoptions` = '$intoptions', `description` = '" . str_replace("'", "''", $description) . "', `access_level` = $access_level, `help` = '$help' WHERE `name` = '$name'");
		}
	}

/*===============================
** Name: getsetting
** Gets an loaded setting
*/	public static function getsetting($name) {
		global $chatBot;
		$name = strtolower($name);

		if (isset($chatBot->settings[$name])) {
	  		return $chatBot->settings[$name];
	  	} else {
	  		return false;
		}
	}

/*===============================
** Name: savesetting
** Saves a setting to the db
*/	public static function savesetting($name, $newsetting = null) {
		global $db;
		global $chatBot;
		$name = strtolower($name);

		if ($newsetting === null) {
			return false;
		}

		if (isset($chatBot->settings[$name])) {
			$db->query("UPDATE settings_<myname> SET `setting` = '" . str_replace("'", "''", $newsetting) . "' WHERE `name` = '$name'");
			$chatBot->settings[$name] = $newsetting;
		} else {
			return false;
		}
	}
}

?>