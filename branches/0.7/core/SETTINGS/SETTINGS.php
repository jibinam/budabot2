<?php
	$MODULE_NAME = "SETTINGS";

	//Commands
	Command::register($MODULE_NAME, "bot_settings.php", "settings", MODERATOR);

	//Setup
	Event::register("setup", $MODULE_NAME, "upload_settings.php", '', 1);
		
	//Help Files
	Help::register("settings", $MODULE_NAME, "settings.txt", MODERATOR, "Change Settings of the Bot.");
?>