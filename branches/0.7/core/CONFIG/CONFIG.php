<?php
	$MODULE_NAME = "CONFIG";

	//Commands
	Command::register("msg", $MODULE_NAME, "cmdcfg.php", "config", MODERATOR);
	Command::register("guild", $MODULE_NAME, "cmdcfg.php", "config", MODERATOR);
	Command::register("priv", $MODULE_NAME, "cmdcfg.php", "config", MODERATOR);

	Command::register("msg", $MODULE_NAME, "searchcmd.php", "searchcmd", MODERATOR);
	Command::register("guild", $MODULE_NAME, "searchcmd.php", "searchcmd", MODERATOR);
	Command::register("priv", $MODULE_NAME, "searchcmd.php", "searchcmd", MODERATOR);
	
	Command::register("msg", $MODULE_NAME, "cmdlist.php", "cmdlist", MODERATOR);
	Command::register("guild", $MODULE_NAME, "cmdlist.php", "cmdlist", MODERATOR);
	Command::register("priv", $MODULE_NAME, "cmdlist.php", "cmdlist", MODERATOR);

	//Help Files
	Help::register("config", $MODULE_NAME, "config.txt", MODERATOR, "Configure Commands/Events of the Bot.");
?>