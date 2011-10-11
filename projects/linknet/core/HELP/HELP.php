<?php 
	$MODULE_NAME = "HELP";

	//Commands
	bot::regcommand("msg", "$MODULE_NAME/general_help.php", "about");
	bot::regcommand("guild", "$MODULE_NAME/general_help.php", "about");
	bot::regcommand("priv", "$MODULE_NAME/general_help.php", "about");
	bot::regcommand("msg", "$MODULE_NAME/general_help.php", "help");
	bot::regcommand("guild", "$MODULE_NAME/general_help.php", "help");
	bot::regcommand("priv", "$MODULE_NAME/general_help.php", "help");
	
	//Help Files
	bot::help($MODULE_NAME, "about", "about.txt", "all", "Some Basic info about the bot");
	bot::help($MODULE_NAME, "rules", "rules.txt", "all", "Some Basic rules of the bot.");
	bot::help($MODULE_NAME, "help", "help.txt", "all", "Bot commands");
?>