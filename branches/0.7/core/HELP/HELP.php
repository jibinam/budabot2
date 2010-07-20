<?php 
	$MODULE_NAME = "HELP";
	$PLUGIN_VERSION = 0.1;
	
	require_once 'Help.class.php';

	//Commands
	$this->regcommand("msg", $MODULE_NAME, "general_help.php", "about", ALL);
	$this->regcommand("guild", $MODULE_NAME, "general_help.php", "about", ALL);
	$this->regcommand("priv", $MODULE_NAME, "general_help.php", "about", ALL);
	$this->regcommand("msg", $MODULE_NAME, "general_help.php", "help", ALL);
	$this->regcommand("guild", $MODULE_NAME, "general_help.php", "help", ALL);
	$this->regcommand("priv", $MODULE_NAME, "general_help.php", "help", ALL);
	
	//Help Files
	Help::register("about", $MODULE_NAME, "about.txt", ALL, "Some Basic infos about the bot.");
?>