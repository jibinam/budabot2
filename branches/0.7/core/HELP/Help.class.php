<?php

class Help {
/*===============================
** Name: help
** Add a help command and display text file in a link.
*/	public static function register($command, $module, $filename, $access_level = ALL, $description = "") {
	  	global $db;
		
		$command = strtolower($command);

		if (Settings::get('debug') > 1) print("Registering Helpfile:($filename) Cmd:($command)\n");
		if (Settings::get('debug') > 2) sleep(1);

		$sql = "SELECT * FROM hlpcfg_<myname> WHERE name = '$command'";
		$db->query($sql);
		if ($db->numrows() == 0) {
			$db->query("INSERT INTO hlpcfg_<myname> (name, module, file, description, access_level, verify) VALUES ('$command', '$module', '$filename', '$description', $access_level, 1)");
		} else {
			$db->query("UPDATE hlpcfg_<myname> SET `verify` = 1, `description` = '$description', file = '$filename' WHERE `name` = '$command'");
		}
	}
	
/*===========================================================================================
** Name: help_lookup
** Find a help topic for a command if it exists
*/	public static function find($sender, $helpcmd) {
		$helpcmd = strtolower($helpcmd);
		$user_access_level = AccessLevel::get_user_access_level($sender);
		$sql = "SELECT name, module, description, file FROM hlpcfg_<myname> WHERE access_level >= $user_access_level AND name = '$helpcmd' ORDER BY module ASC";
		$db->query($sql);
		if ($db->numrows() == 0) {
			return FALSE;
		} else {
			$row = $db->fObject();
			$data = file_get_contents($row->file);
			$helpcmd = ucfirst($helpcmd);
			$msg = Links::makeLink("Help($helpcmd)", $data);
			return $msg;
		}
	}
}

?>
