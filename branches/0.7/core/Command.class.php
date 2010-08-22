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
			Logger::log(__FILE__, "$module/$filename Invalid args for command '$command'", ERROR);
			return;
		}
		
		// TODO
		//if (($filename = Util::verify_filename($filename)) == FALSE) {
		//	Logger::log(__FILE__, "Invalid filename: '$filename'", WARN);
		//}

		$command = strtolower($command);
		$description = str_replace("'", "''", $description);

		Logger::log(__FILE__, "$module/$filename Adding Command '$command'", DEBUG);

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
			// TODO
			$status = Settings::get("default module status");
			$status = 1;
		
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
					'$command',
					$status,
					{$access_level['tell']},
					$status,
					{$access_level['guild']},
					$status,
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
		return $db->query($sql, true);
	}
	
	public static function find_command_for_user(&$player, $cmd, $type) {
		global $db;
		
		if ($type == 'msg') {
			$type = 'tell';
		}
		
		$sql = "SELECT * FROM cmdcfg_<myname> WHERE `cmd` = '$cmd' AND {$type}_status = 1 AND {$type}_access_level >= $player->access_level";
		return $db->query($sql, true);
	}
	
	public static function fire_command(&$params) {
		global $chatBot;
		global $db;

		forEach ($params as $key => $value) {
			$$key = &$params[$key];
		}
		
		// Break down in to words.
		$words	= explode(' ', $message, 2);
		$command = Command::find_command_for_user($player, $words[0], $type);

		// Upload Command File or return error message
		if ($command == false) {
			$chatBot->send("Error! Unknown command or Access denied! for more info try /tell <myname> help", $sendto);
			Settings::add_spam($player, 20);
		} else {
			$syntax_error = false;
			$msg = "";
			$path = Util::get_full_path($command);
			Logger::log(__FILE__, "Command: '$type' File: '$path'", DEBUG);
			require $path;
			if ($syntax_error == true) {
				if (($output = Help::find($player, $words[0])) !== FALSE) {
					$chatBot->send("Error! Check your syntax " . $output, $sendto);
				} else {
					$chatBot->send("Error! Check your syntax or for more info try /tell <myname> help", $sendto);
				}
			}
			Settings::add_spam($player, 10);
		}
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
}

?>