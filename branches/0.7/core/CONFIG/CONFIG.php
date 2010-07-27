<?php
	$MODULE_NAME = "CONFIG";

	//Commands
	Command::register("", $MODULE_NAME, "cmdcfg.php", "config", MODERATOR);
	Command::register("", $MODULE_NAME, "searchcmd.php", "searchcmd", MODERATOR);
	Command::register("", $MODULE_NAME, "cmdlist.php", "cmdlist", MODERATOR);

	//Help Files
	Help::register("config", $MODULE_NAME, "config.txt", MODERATOR, "Configure Commands/Events of the Bot.");
?>