<?php 
	$MODULE_NAME = "HELP";

	//Commands
	Command::register($MODULE_NAME, "general_help.php", "about", ALL, 'shows info about the bot', 1);
	Command::register($MODULE_NAME, "general_help.php", "help", ALL, 'shows all help topics', 1);
	
	//Help Files
	Help::register("about", $MODULE_NAME, "about.txt", ALL, "Some Basic infos about the bot.");
?>