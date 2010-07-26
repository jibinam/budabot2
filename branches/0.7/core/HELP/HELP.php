<?php 
	$MODULE_NAME = "HELP";

	//Commands
	Command::register("msg", $MODULE_NAME, "general_help.php", "about", ALL);
	Command::register("guild", $MODULE_NAME, "general_help.php", "about", ALL);
	Command::register("priv", $MODULE_NAME, "general_help.php", "about", ALL);
	Command::register("msg", $MODULE_NAME, "general_help.php", "help", ALL);
	Command::register("guild", $MODULE_NAME, "general_help.php", "help", ALL);
	Command::register("priv", $MODULE_NAME, "general_help.php", "help", ALL);
	
	//Help Files
	Help::register("about", $MODULE_NAME, "about.txt", ALL, "Some Basic infos about the bot.");
?>