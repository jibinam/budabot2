<?php
	$MODULE_NAME = "PRIV_TELL_LIMIT";
	
	DB::loadSqlFile($MODULE_NAME, 'whitelist');
	
	//Set/Show Limits
	Command::register($MODULE_NAME, "config.php", "limits", MODERATOR, '', 1);
	Command::register($MODULE_NAME, "config.php", "limit", MODERATOR, '', 1);
	Command::register($MODULE_NAME, "whitelist.php", "whitelist", MODERATOR, '', 1);

	//Set/Show minlvl for Tells
	Command::register($MODULE_NAME, "set_limits_tells.php", "tminlvl", MODERATOR, '', 1);

	//Set/Show general limit for Tells
	Command::register($MODULE_NAME, "set_limits_tells.php", "topen", MODERATOR, '', 1);

	//Set/Show faction limit for Tells
	Command::register($MODULE_NAME, "set_limits_tells.php", "tfaction", MODERATOR, '', 1);

	//Set/Show minlvl for privategroup
	Command::register($MODULE_NAME, "set_limits_priv.php", "minlvl", MODERATOR, '', 1);

	//Set/Show general limit for privategroup
	Command::register($MODULE_NAME, "set_limits_priv.php", "open", MODERATOR, '', 1);

	//Set/Show faction limit for privategroup
	Command::register($MODULE_NAME, "set_limits_priv.php", "faction", MODERATOR, '', 1);

	//Settings
	Settings::add("priv_req_lvl", $MODULE_NAME, "Private Channel Min Level Limit", "noedit", "0", "none", "0", MODERATOR, "help_minlvl.txt", 1);
	Settings::add("priv_req_faction", $MODULE_NAME, "Private Channel Faction Limit", "noedit", "all", "none", "0", MODERATOR, "help_faction.txt", 1);
	Settings::add("priv_req_open", $MODULE_NAME, "Private Channel General Limit", "noedit", "all", "none", "0", MODERATOR, "help_open.txt", 1);
	Settings::add("priv_req_maxplayers", $MODULE_NAME, "Maximum Players in the PrivGroup", "noedit", "0", "none", "0", MODERATOR, "help_maxplayers.txt", 1);

	Settings::add("tell_req_lvl", $MODULE_NAME, "Tells Min Level", "noedit", "0", "none", "0", MODERATOR, "help_tminlvl.txt", 1);
	Settings::add("tell_req_faction", $MODULE_NAME, "Tell Faction Limit", "noedit", "all", "none", "0", MODERATOR, "help_tfaction.txt", 1);
	Settings::add("tell_req_open", $MODULE_NAME, "Tell General Limit", "noedit", "all", "none", "0", MODERATOR, "help_topen.txt", 1);

	//Help File
	Help::register($MODULE_NAME, "help.txt", "priv_tell_limits", MODERATOR, "Set Limits for tells and PrivGroup.");
?>