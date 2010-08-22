<?php
	$MODULE_NAME = "SETTINGS";

	//Commands
	Command::register($MODULE_NAME, "bot_settings.php", "settings", MODERATOR, 'shows all the settings for the bot', 1);
		
	//Help Files
	Help::register($MODULE_NAME, "settings.txt", "settings", MODERATOR, "Change Settings of the Bot.");
?>