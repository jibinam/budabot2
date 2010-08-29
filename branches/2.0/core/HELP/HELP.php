<?php 
	$MODULE_NAME = "HELP";

	//Commands
	Command::register($MODULE_NAME, "general_help.php", "about", ALL, 'shows info about the bot', 1);
	Command::register($MODULE_NAME, "general_help.php", "help", ALL, 'shows all help topics', 1);
	
	//Help Files
	Help::register($MODULE_NAME, "about.txt", "about", ALL, "Some Basic infos about the bot.");
?>