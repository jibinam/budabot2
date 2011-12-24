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
?>