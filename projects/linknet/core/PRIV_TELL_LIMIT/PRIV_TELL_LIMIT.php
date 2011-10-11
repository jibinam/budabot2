<?php
	$MODULE_NAME = "PRIV_TELL_LIMIT";
	
	require_once 'Whitelist.class.php';
	
	bot::loadSqlFile($MODULE_NAME, 'whitelist');
	
	//Set/Show Limits
	bot::regcommand("msg", "$MODULE_NAME/config.php", "limits", "admin");
	bot::regcommand("msg", "$MODULE_NAME/config.php", "limit", "admin");
	bot::regcommand("msg", "$MODULE_NAME/whitelist.php", "whitelist", "admin");
	
	bot::regcommand("priv", "$MODULE_NAME/config.php", "limits", "admin");
	bot::regcommand("priv", "$MODULE_NAME/config.php", "limit", "admin");
	bot::regcommand("priv", "$MODULE_NAME/whitelist.php", "whitelist", "admin");
	
	bot::regcommand("guild", "$MODULE_NAME/config.php", "limits", "admin");
	bot::regcommand("guild", "$MODULE_NAME/config.php", "limit", "admin");
	bot::regcommand("guild", "$MODULE_NAME/whitelist.php", "whitelist", "admin");

	//Set/Show minlvl for Tells
	bot::regcommand("msg", "$MODULE_NAME/set_limits_tells.php", "tminlvl", "admin");
	bot::regcommand("priv", "$MODULE_NAME/set_limits_tells.php", "tminlvl", "admin");

	//Set/Show general limit for Tells
	bot::regcommand("msg", "$MODULE_NAME/set_limits_tells.php", "topen", "admin");
	bot::regcommand("priv", "$MODULE_NAME/set_limits_tells.php", "topen", "admin");

	//Set/Show faction limit for Tells
	bot::regcommand("msg", "$MODULE_NAME/set_limits_tells.php", "tfaction", "admin");
	bot::regcommand("priv", "$MODULE_NAME/set_limits_tells.php", "tfaction", "admin");

	//Set/Show minlvl for private channel
	bot::regcommand("msg", "$MODULE_NAME/set_limits_priv.php", "minlvl", "admin");
	bot::regcommand("priv", "$MODULE_NAME/set_limits_priv.php", "minlvl", "admin");

	//Set/Show general limit for private channel
	bot::regcommand("msg", "$MODULE_NAME/set_limits_priv.php", "openchannel", "admin");
	bot::regcommand("priv", "$MODULE_NAME/set_limits_priv.php", "openchannel", "admin");

	//Set/Show faction limit for private channel
	bot::regcommand("msg", "$MODULE_NAME/set_limits_priv.php", "faction", "admin");
	bot::regcommand("priv", "$MODULE_NAME/set_limits_priv.php", "faction", "admin");

	//Settings
	bot::addsetting($MODULE_NAME, "priv_req_lvl", "Private Channel Min Level Limit", "noedit", "0", "none", "0", "admin", "$MODULE_NAME/help_minlvl.txt");
	bot::addsetting($MODULE_NAME, "priv_req_faction", "Private Channel Faction Limit", "noedit", "all", "none", "0", "admin", "$MODULE_NAME/help_faction.txt");
	bot::addsetting($MODULE_NAME, "priv_req_open", "Private Channel General Limit", "noedit", "all", "none", "0", "admin", "$MODULE_NAME/help_open.txt");
	bot::addsetting($MODULE_NAME, "priv_req_maxplayers", "Maximum Players in the PrivGroup", "noedit", "0", "none", "0", "admin", "$MODULE_NAME/help_maxplayers.txt");

	bot::addsetting($MODULE_NAME, "tell_req_lvl", "Tells Min Level", "noedit", "0", "none", "0", "admin", "$MODULE_NAME/help_tminlvl.txt");
	bot::addsetting($MODULE_NAME, "tell_req_faction", "Tell Faction Limit", "noedit", "all", "none", "0", "admin", "$MODULE_NAME/help_tfaction.txt");
	bot::addsetting($MODULE_NAME, "tell_req_open", "Tell General Limit", "noedit", "all", "none", "0", "admin", "$MODULE_NAME/help_topen.txt");

	//Help File
	bot::help($MODULE_NAME, "priv_tell_limits", "help.txt", "mod", "Set Limits for tells and PrivGroup");
?>