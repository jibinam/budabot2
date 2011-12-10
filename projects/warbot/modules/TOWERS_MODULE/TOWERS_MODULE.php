<?php
	require_once 'Towers.class.php';
	require_once 'functions.php';

	$MODULE_NAME = "TOWERS_MODULE";

	DB::loadSQLFile($MODULE_NAME, "tower_attack");
	DB::loadSQLFile($MODULE_NAME, "scout_info");
	DB::loadSQLFile($MODULE_NAME, "tower_site");
	DB::loadSQLFile($MODULE_NAME, "tower_info");

	Command::register($MODULE_NAME, "", "scout.php", "forcescout", "all", "Adds tower info to watch list (bypasses some of the checks)");
	Command::register($MODULE_NAME, "", "scout.php", "scout", "all", "Adds tower info to watch list");
	Command::register($MODULE_NAME, "", "scouthistory.php", "scouthistory", "all", "Shows status of towers");

	Command::register($MODULE_NAME, "", "opentimes.php", "opentimes", "all", "Shows status of towers");
	Command::register($MODULE_NAME, "", "lc.php", "lc", "all", "Shows status of towers");

	Command::register($MODULE_NAME, "", "open.php", "open", "all", "Shows status of clan towers");
	Command::register($MODULE_NAME, "", "open.php", "openomni", "all", "Shows status of omni towers");

	Command::register($MODULE_NAME, "", "attacks.php", "attacks", "all", "Shows the last Tower Attack messages");
	Command::register($MODULE_NAME, "", "attacks.php", "battle", "all", "Shows the last Tower Attack messages");
	Command::register($MODULE_NAME, "", "attacks.php", "battles", "all", "Shows the last Tower Attack messages");

  	Command::register($MODULE_NAME, "", "victory.php", "victory", "all", "Shows the last Tower Battle results");

	Command::register($MODULE_NAME, "", "scoutneeded.php", "scoutneeded", "all", "Lists all sites that need to be scouted");
	
	Command::register($MODULE_NAME, "", "basetopic.php", "basetopic", "all", "Set topic to a specific base topic");
	
	Setting::add($MODULE_NAME, "tower_attack_spam", "Layout types when displaying tower attacks", "edit", "options", "1", "off;compact;normal;full", '0;1;2;3');
	Setting::add($MODULE_NAME, "tower_faction_def", "Display certain factions defending", "edit", "options", "7", "none;clan;neutral;clan+neutral;omni;clan+omni;neutral+omni;all", '0;1;2;3;4;5;6;7');
	Setting::add($MODULE_NAME, "tower_faction_atk", "Display certain factions attacking", "edit", "options", "7", "none;clan;neutral;clan+neutral;omni;clan+omni;neutral+omni;all", '0;1;2;3;4;5;6;7');
	Setting::add($MODULE_NAME, "check_org_name_on_scout", "Verify that the org name has been attacked", "edit", "options", "1", "false;true", '0;1', "mod");
	Setting::add($MODULE_NAME, "check_close_time_on_scout", "Verify that close time less than 1 hour later than the time is was destroyed", "edit", "options", "1", "false;true", '0;1');

	Event::register($MODULE_NAME, "towers", "attack_messages.php", "none", "Record attack messages");
	Event::register($MODULE_NAME, "towers", "victory_messages.php", "none", "Record victory messages");

	// help files
	Help::register($MODULE_NAME, "attacks", "attacks.txt", "guild", "Show attack message commands and options");
	Help::register($MODULE_NAME, "victory", "victory.txt", "guild", "Show victory message commands and options");
	Help::register($MODULE_NAME, "lc", "lc.txt", "all", "How to use land control commands");
?>
