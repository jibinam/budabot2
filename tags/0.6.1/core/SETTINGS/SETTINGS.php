<?
$MODULE_NAME = "SETTINGS";
$PLUGIN_VERSION = 0.1;

	//Commands
	bot::regcommand("msg", "$MODULE_NAME/bot_settings.php", "settings", "mod");

	//Setup
	bot::regevent("setup", "$MODULE_NAME/setup.php");
	bot::regevent("setup", "$MODULE_NAME/upload_settings.php");
		
	//Help Files
	bot::help("settings", "$MODULE_NAME/settings.txt", "mod", "Change Settings of the Bot.", "Configuration of the Bot");
?>