<?php
	$MODULE_NAME = "TOWERS_MODULE";

	//Tower messages
    Event::register("towers", $MODULE_NAME, "towers_messages.php", "none", "Show Attack Messages"); 
	
	Command::register("", $MODULE_NAME, "towers_result.php", "battle", ALL, "Shows the last Tower Attack messages");
	Command::register("", $MODULE_NAME, "towers_result.php", "battles", ALL, "Shows the last Tower Attack messages");  // alias for !battle
  	Command::register("", $MODULE_NAME, "towers_result.php", "victory", ALL, "Shows the last Tower Battle results");

	//Land Control Areas
  	Command::register("", $MODULE_NAME, "land_control_areas.php", "lca", ALL, "Shows Infos about Land Control Areas");

	Settings::add("tower_attack_spam", $MODULE_NAME, "Layout types when displaying tower attacks", "edit", "1", "off;compact;normal;full", '0;1;2;3', MODERATOR);
	Settings::add("tower_faction_def", $MODULE_NAME, "Display certain factions defending", "edit", "7", "none;clan;neutral;clan+neutral;omni;clan+omni;neutral+omni;all", '0;1;2;3;4;5;6;7', MODERATOR);
	Settings::add("tower_faction_atk", $MODULE_NAME, "Display certain factions attacking", "edit", "7", "none;clan;neutral;clan+neutral;omni;clan+omni;neutral+omni;all", '0;1;2;3;4;5;6;7', MODERATOR);

	//Setup
	DB::loadSQLFile($MODULE_NAME, "towerranges");
	
	//Help files
	Help::register("towers", $MODULE_NAME, "towers.txt", ALL, "Show Tower messages");
	Help::register("lca", $MODULE_NAME, "lca.txt", ALL, "Show Infos about Land Control Areas");
?>
