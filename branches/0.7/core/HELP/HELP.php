<?php 
	$MODULE_NAME = "HELP";

	//Commands
	Command::register($MODULE_NAME, "general_help.php", "about", ALL);
	Command::register($MODULE_NAME, "general_help.php", "help", ALL);
	
	//Help Files
	Help::register("about", $MODULE_NAME, "about.txt", ALL, "Some Basic infos about the bot.");
?>