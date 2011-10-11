<?php


	$MODULE_NAME = "HELPBOT_MODULE";

	bot::loadSQLFile($MODULE_NAME, "playfields");

	bot::command("", "$MODULE_NAME/time.php", "time", "all", "Shows the time in the different timezones");
	bot::command("", "$MODULE_NAME/whois.php", "whois", "all", "Char Infos (only current dim)");
	bot::command("", "$MODULE_NAME/whois.php", "whoisall", "all", "Char Infos (all dim)");
	bot::command("", "$MODULE_NAME/whois.php", "whoisorg", "all", "Org Infos");
	bot::command("", "$MODULE_NAME/calc.php", "calc", "all", "Calculator");
	bot::command("", "$MODULE_NAME/player_history.php", "history", "all", "Show a history of a player");
	bot::command("", "$MODULE_NAME/playfields.php", "playfields", "all", "Shows all the playfields including IDs and short names");
	bot::command("", "$MODULE_NAME/waypoint.php", "waypoint", "all", "Creats a waypoint link");

	// Help files
	bot::help($MODULE_NAME, "whois", "whois.txt", "all", "Show char stats at current and all dimensions");
    bot::help($MODULE_NAME, "calc", "calculator.txt", "all", "Calculator");
?>
