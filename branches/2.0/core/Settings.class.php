<?php

/*
`name` VARCHAR(30) NOT NULL
`module` VARCHAR(50)
`mode` VARCHAR(10)
`is_core` TINYINT NOT NULL
`setting` VARCHAR(50) DEFAULT '0'
`options` VARCHAR(50) Default '0'
`intoptions` VARCHAR(50) DEFAULT '0'
`description` VARCHAR(50) NOT NULL DEFAULT ''
`source` VARCHAR(5)
`access_level` INT DEFAULT 0
`help` VARCHAR(60)
`verify` INT DEFAULT 1
*/

class Settings {

/*===============================
** Name: add
** Adds a setting to the list
*/	public static function add($name, $module, $description = 'none', $mode = 'hide', $setting = 'none', $options = 'none', $intoptions = '0', $access_level = MODERATOR, $help = '', $is_core = 0) {
		global $db;
		global $chatBot;

		$name = strtolower($name);

		$params = array(
			':name' => $name,
			':module' => $module,
			':mode' => $mode,
			':setting' => $setting,
			':options' => $options,
			':intoptions' => $intoptions,
			':description' => $description,
			':source' => 'db',
			':access_level' => $access_level,
			':help' => $help,
			':is_core' => $is_core
		);

		if (Settings::get($name) != false) {
			$sql =
				"UPDATE settings_<myname> SET
					`module` = :module,
					`mode` = :mode,
					`options` = :options,
					`intoptions` = :intoptions,
					`description` = :description,
					`access_level` = :access_level,
					`help` = :help,
					`is_core` = :is_core
				WHERE
					`name` = :name
			";
		} else {
			$sql = "
				INSERT INTO settings_<myname> (
					`name`,
					`module`,
					`mode`,
					`setting`,
					`options`,
					`intoptions`,
					`description`,
					`source`,
					`access_level`,
					`help`, 
					`is_core`
				) VALUES (
					:name,
					:module,
					:mode,
					:setting,
					:options,
					:intoptions,
					:description,
					:source,
					:access_level,
					:help,
					:is_core
				)";
		}
		$db->prepared_statement($sql, $params);
	}

/*===============================
** Name: get
** Gets a loaded setting
*/	public static function get($name) {
		global $db;
		global $chatBot;

		$params = array(':name' => $name);
		$sql = "SELECT setting FROM settings_<myname> WHERE `name` = :name";
		$row = $db->prepared_statement($sql, $params, true);
		return $row->setting;
	}
	
	public static function is_ignored(&$player) {
		global $chatBot;
		$name = ucfirst(strtolower($name));
	
		if (isset($chatBot->settings['Ignored'][$name])) {
	  		return true;
	  	} else {
	  		return false;
		}
	}
	
	public static function is_banned(&$player) {
		// TODO
		return false;
	}
	
	public static function is_spammer(&$player) {
		// TODO
		return false;
	}
	
	public static function add_spam(&$player) {
		// TODO
		// check if spam control is enabled
	}

/*===============================
** Name: save
** Saves a setting to the db
*/	public static function save($name, $newsetting = null) {
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