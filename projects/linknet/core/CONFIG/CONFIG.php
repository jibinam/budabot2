<?php
	$MODULE_NAME = "CONFIG";

	//Commands
	bot::regcommand("msg", "$MODULE_NAME/cmdcfg.php", "config", "admin");
	bot::regcommand("guild", "$MODULE_NAME/cmdcfg.php", "config", "admin");
	bot::regcommand("priv", "$MODULE_NAME/cmdcfg.php", "config", "admin");

	bot::regcommand("msg", "$MODULE_NAME/searchcmd.php", "searchcmd", "mod");
	bot::regcommand("guild", "$MODULE_NAME/searchcmd.php", "searchcmd", "mod");
	bot::regcommand("priv", "$MODULE_NAME/searchcmd.php", "searchcmd", "mod");

	//Help Files
	bot::help($MODULE_NAME, "config", "config.txt", "mod", "Configure Commands/Events of the Bot");
?>