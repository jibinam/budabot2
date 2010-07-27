<?php
	$MODULE_NAME = "SETTINGS";

	//Commands
	Command::register("msg", $MODULE_NAME, "bot_settings.php", "settings", MODERATOR);
	Command::register("priv", $MODULE_NAME, "bot_settings.php", "settings", MODERATOR);
	Command::register("guild", $MODULE_NAME, "bot_settings.php", "settings", MODERATOR);

	//Setup
	Event::register("setup", $MODULE_NAME, "upload_settings.php");
		
	//Help Files
	Help::register("settings", $MODULE_NAME, "settings.txt", MODERATOR, "Change Settings of the Bot.");
?>