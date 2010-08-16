<?php

/*
`module` VARCHAR(50) NOT NULL
`regex` VARCHAR(255)
`file` VARCHAR(255) NOT NULL
`is_core` TINYINT NOT NULL
`cmd` VARCHAR(25) NOT NULL
`tell_status` INT DEFAULT 0
`tell_access_level` INT DEFAULT 0
`guild_status` INT DEFAULT 0
`guild_access_level` INT DEFAULT 0
`priv_status` INT DEFAULT 0
`priv_access_level` INT DEFAULT 0
`description` VARCHAR(50) NOT NULL DEFAULT ''
`verify` INT DEFAULT 1
*/

class Command {

/*===============================
** Name: Command
** 	Register a command
*/	public static function register($module, $filename, $command, $access_level, $description = '', $is_core = 0) {
		global $db;

		if (!Command::processCommandArgs($access_level)) {
			Logger::log(__FILE__, "invalid args for command '$command'", ERROR);
			return;
		}
		
		if (($filename = Util::verify_filename($filename)) == FALSE) {
			Logger:log(__FILE__, "Invalid filename: '$filename'", WARN);
		}

		$command = strtolower($command);
		$description = str_replace("'", "''", $description);

		Logger:log(__FILE__, "Adding Command to list:($command) File:($filename)", DEBUG);

		if (Command::find_command($command) != false) {
			$sql = "
				UPDATE
					cmdcfg_<myname>
				SET
					`module` = '$module',
					`regex` = '',
					`file` = '$filename',
					`is_core` = $is_core,
					`description` = '$description',
					`verify` = 1
				WHERE
					`cmd` = '$command'";
		} else {
			$sql = "
				INSERT INTO cmdcfg_<myname> (
					`module`,
					`regex`,
					`file`,
					`is_core`,
					`cmd`,
					`tell_status`,
					`tell_access_level`,
					`guild_status`,
					`guild_access_level`,
					`priv_status`,
					`priv_access_level`,
					`description`,
					`verify`
				) VALUES (
					'$module',
					'',
					'$filename',
					$is_core,
					'$command'
					". Settings::get("default module status") .",
					{$access_level['tell']},
					". Settings::get("default module status") .",
					{$access_level['guild']},
					". Settings::get("default module status") .",
					{$access_level['priv']},
					'$description',
					1
				)";
		}
		$db->query($sql);
	}
	
	public static function find_command($name) {
		global $db;
		
		$sql = "SELECT * from cmdcfg_<myname> WHERE `cmd` = '$name'";
		$db->query($sql);
		return $db->fObject();
	}

/*===============================
** Name: processCommandType
** 	Returns a command type in the proper format
*/	public static function processCommandArgs(&$access_level) {
		// tell, priv, guild

		$access_level = explode(' ', $access_level);
		if (count($access_level) == 1) {
			$access_level['tell'] = $access_level[0];
			$access_level['priv'] = $access_level[0];
			$access_level['guild'] = $access_level[0];
			return true;
		} else if (count($access_level) == 3) {
			$access_level['tell'] = $access_level[0];
			$access_level['priv'] = $access_level[1];
			$access_level['guild'] = $access_level[2];
			return true;
		} else {
			return false;
		}
	}

/*===============================
** Name: subcommand
** 	Register a subcommand
*/	public static function subcommand($module, $filename, $command, $access_level = ALL, $dependson, $description = 'none') {
		global $db;

		if (!$this->processCommandArgs($access_level)) {
			Logger::log(__FILE__, "invalid args for subcommand '$command'", ERROR);
			return;
		}

		$command = strtolower($command);
	  	
		if ($command != NULL) // Change commands to lower case.
			$command = strtolower($command);

		for ($i = 0; $i < count($type); $i++) {
			Logger:log(__FILE__, "Adding Subcommand to list:($command) File:($filename) Admin:($access_level[$i]) Type:($type[$i])", DEBUG);
			
			if ($this->existing_subcmds[$type[$i]][$command] == true) {
				$db->query("UPDATE cmdcfg_<myname> SET `module` = '$module', `verify` = 1, `file` = '$filename', `description` = '$description', `dependson` = '$dependson' WHERE `cmd` = '$command' AND `type` = '{$type[$i]}'");
			} else {
				$db->query("INSERT INTO cmdcfg_<myname> (`module`, `type`, `file`, `cmd`, `access_level`, `description`, `verify`, `cmdevent`, `dependson`, `status`) VALUES ('$module', '{$type[$i]}', '$filename', '$command', $access_level, '$description', 1, 'subcmd', '$dependson', '".Settings::get("default module status")."')");
			}
		}
	}
}

?>