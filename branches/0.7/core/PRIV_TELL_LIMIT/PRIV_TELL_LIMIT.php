<?php
	$MODULE_NAME = "PRIV_TELL_LIMIT";
	
	require_once 'Whitelist.class.php';
	
	$this->loadSqlFile($MODULE_NAME, 'whitelist');
	
	//Set/Show Limits
	$this->regcommand("msg", $MODULE_NAME, "config.php", "limits", MODERATOR);
	$this->regcommand("msg", $MODULE_NAME, "config.php", "limit", MODERATOR);
	$this->regcommand("msg", $MODULE_NAME, "whitelist.php", "whitelist", MODERATOR);
	
	$this->regcommand("priv", $MODULE_NAME, "config.php", "limits", MODERATOR);
	$this->regcommand("priv", $MODULE_NAME, "config.php", "limit", MODERATOR);
	$this->regcommand("msg", $MODULE_NAME, "whitelist.php", "whitelist", MODERATOR);
	
	$this->regcommand("guild", $MODULE_NAME, "config.php", "limits", MODERATOR);
	$this->regcommand("guild", $MODULE_NAME, "config.php", "limit", MODERATOR);
	$this->regcommand("msg", $MODULE_NAME, "whitelist.php", "whitelist", MODERATOR);

	//Set/Show minlvl for Tells
	$this->regcommand("msg", $MODULE_NAME, "set_limits_tells.php", "tminlvl", MODERATOR);
	$this->regcommand("priv", $MODULE_NAME, "set_limits_tells.php", "tminlvl", MODERATOR);

	//Set/Show general limit for Tells
	$this->regcommand("msg", $MODULE_NAME, "set_limits_tells.php", "topen", MODERATOR);
	$this->regcommand("priv", $MODULE_NAME, "set_limits_tells.php", "topen", MODERATOR);

	//Set/Show faction limit for Tells
	$this->regcommand("msg", $MODULE_NAME, "set_limits_tells.php", "tfaction", MODERATOR);
	$this->regcommand("priv", $MODULE_NAME, "set_limits_tells.php", "tfaction", MODERATOR);

	//Set/Show minlvl for privategroup
	$this->regcommand("msg", $MODULE_NAME, "set_limits_priv.php", "minlvl", MODERATOR);
	$this->regcommand("priv", $MODULE_NAME, "set_limits_priv.php", "minlvl", MODERATOR);

	//Set/Show general limit for privategroup
	$this->regcommand("msg", $MODULE_NAME, "set_limits_priv.php", "open", MODERATOR);
	$this->regcommand("priv", $MODULE_NAME, "set_limits_priv.php", "open", MODERATOR);

	//Set/Show faction limit for privategroup
	$this->regcommand("msg", $MODULE_NAME, "set_limits_priv.php", "faction", MODERATOR);
	$this->regcommand("priv", $MODULE_NAME, "set_limits_priv.php", "faction", MODERATOR);

	//Set/Show faction limit for privategroup
	$this->regcommand("msg", $MODULE_NAME, "set_limits_priv.php", "faction", MODERATOR);
	$this->regcommand("priv", $MODULE_NAME, "set_limits_priv.php", "faction", MODERATOR);

	//Settings
	Settings::add("priv_req_lvl", $MODULE_NAME, "Private Channel Min Level Limit", "noedit", "0", "none", "0", MODERATOR, "help_minlvl.txt");
	Settings::add("priv_req_faction", $MODULE_NAME, "Private Channel Faction Limit", "noedit", "all", "none", "0", MODERATOR, "help_faction.txt");
	Settings::add("priv_req_open", $MODULE_NAME, "Private Channel General Limit", "noedit", "all", "none", "0", MODERATOR, "help_open.txt");
	Settings::add("priv_req_maxplayers", $MODULE_NAME, "Maximum Players in the PrivGroup", "noedit", "0", "none", "0", MODERATOR, "help_maxplayers.txt");

	Settings::add("tell_req_lvl", $MODULE_NAME, "Tells Min Level", "noedit", "0", "none", "0", MODERATOR, "help_tminlvl.txt");
	Settings::add("tell_req_faction", $MODULE_NAME, "Tell Faction Limit", "noedit", "all", "none", "0", MODERATOR, "help_tfaction.txt");
	Settings::add("tell_req_open", $MODULE_NAME, "Tell General Limit", "noedit", "all", "none", "0", MODERATOR, "help_topen.txt");

	//Help File
	$this->help("priv_tell_limits", $MODULE_NAME, "help.txt", MODERATOR, "Set Limits for tells and PrivGroup.");
?>