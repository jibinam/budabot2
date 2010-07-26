<?php

class Command {

/*===============================
** Name: Command
** 	Register a command
*/	public static function register($type, $module, $filename, $command, $access_level, $description = '', $is_core = false) {
		global $db;

		if (!Command::processCommandArgs($type, $access_level)) {
			echo "invalid args for command '$command'!!\n";
			return;
		}

		$command = strtolower($command);
		$description = str_replace("'", "''", $description);
		$is_core = $is_core ? 1 : 0;

		if (Settings::get('debug') > 1) print("Adding Command to list:($command) File:($filename)\n");
		if (Settings::get('debug') > 2) sleep(1);

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
	
	public static function find_command($name, $type = null) {
		global $db;
		
		if ($type == null) {
			$sql = "SELECT * from cmdcfg_<myname> WHERE `cmd` = '$name'";
		} else {
			$sql = "SELECT * from cmdcfg_<myname> WHERE `cmd` = '$name' AND $type = 1";
		}
		$db->query($sql);
		return $db->fObject();
	}

/*===============================
** Name: processCommandType
** 	Returns a command type in the proper format
*/	public static function processCommandArgs(&$type, &$access_level) {
		if ($type == "") {
			$type = array("msg", "priv", "guild");
		} else {
			$type = explode(' ', $type);
		}

		$admin = explode(' ', $access_level);
		if (count($admin) == 1) {
			$admin = array_fill(0, count($type), $admin[0]);
		} else if (count($admin) != count($type)) {
			echo "ERROR! the number of type arguments does not equal the number of admin arguments for command/subcommand registration!";
			return false;
		}
		return true;
	}

/*===============================
** Name: subcommand
** 	Register a subcommand
*/	public static function subcommand($type, $module, $filename, $command, $access_level = ALL, $dependson, $description = 'none') {
		global $db;

		if (!$this->processCommandArgs($type, $access_level)) {
			echo "invalid args for subcommand '$command'!!\n";
			return;
		}

		$command = strtolower($command);
	  	
		if ($command != NULL) // Change commands to lower case.
			$command = strtolower($command);

		for ($i = 0; $i < count($type); $i++) {
			if (Settings::get('debug') > 1) print("Adding Subcommand to list:($command) File:($filename)\n");
			if (Settings::get('debug') > 1) print("                    Admin:($access_level[$i]) Type:({$type[$i]})\n");
			if (Settings::get('debug') > 2) sleep(1);
			
			if ($this->existing_subcmds[$type[$i]][$command] == true) {
				$db->query("UPDATE cmdcfg_<myname> SET `module` = '$module', `verify` = 1, `file` = '$filename', `description` = '$description', `dependson` = '$dependson' WHERE `cmd` = '$command' AND `type` = '{$type[$i]}'");
			} else {
				$db->query("INSERT INTO cmdcfg_<myname> (`module`, `type`, `file`, `cmd`, `access_level`, `description`, `verify`, `cmdevent`, `dependson`, `status`) VALUES ('$module', '{$type[$i]}', '$filename', '$command', $access_level, '$description', 1, 'subcmd', '$dependson', '".Settings::get("default module status")."')");
			}
		}
	}
}

?>