<?php
	$MODULE_NAME = "CONFIG";

	//Commands
	Command::register($MODULE_NAME, "cmdcfg.php", "config", MODERATOR, 'shows options to configure the bot', 1);
	Command::register($MODULE_NAME, "searchcmd.php", "searchcmd", MODERATOR, 'shows the module that contains a specified command', 1);

	//Help Files
	Help::register($MODULE_NAME, "config.txt", "config", MODERATOR, "Configure Commands/Events of the Bot.");
?>