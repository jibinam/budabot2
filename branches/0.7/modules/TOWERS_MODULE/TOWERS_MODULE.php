<?php
	$MODULE_NAME = "TOWERS_MODULE";
	$PLUGIN_VERSION = 0.1;

	//Tower messages
    $this->event("towers", $MODULE_NAME, "towers_messages.php", "none", "Show Attack Messages"); 
	
	$this->command("", $MODULE_NAME, "towers_result.php", "battle", ALL, "Shows the last Tower Attack messages");
	$this->command("", $MODULE_NAME, "towers_result.php", "battles", ALL, "Shows the last Tower Attack messages");  // alias for !battle
  	$this->command("", $MODULE_NAME, "towers_result.php", "victory", ALL, "Shows the last Tower Battle results");

	$this->regGroup("Tower_Battle", $MODULE_NAME, "Show Tower Attack Results", "battle", "victory");
	
	//Land Control Areas
  	$this->command("", $MODULE_NAME, "land_control_areas.php", "lca", ALL, "Shows Infos about Land Control Areas");

	Settings::add("tower_attack_spam", $MODULE_NAME, "Layout types when displaying tower attacks", "edit", "1", "off;compact;normal;full", '0;1;2;3', MODERATOR);
	Settings::add("tower_faction_def", $MODULE_NAME, "Display certain factions defending", "edit", "7", "none;clan;neutral;clan+neutral;omni;clan+omni;neutral+omni;all", '0;1;2;3;4;5;6;7', MODERATOR);
	Settings::add("tower_faction_atk", $MODULE_NAME, "Display certain factions attacking", "edit", "7", "none;clan;neutral;clan+neutral;omni;clan+omni;neutral+omni;all", '0;1;2;3;4;5;6;7', MODERATOR);

	//Setup
	$this->loadSQLFile($MODULE_NAME, "towerranges");
	
	//Help files
	$this->help("towers", $MODULE_NAME, "towers.txt", ALL, "Show Tower messages");
	$this->help("lca", $MODULE_NAME, "lca.txt", ALL, "Show Infos about Land Control Areas");
?>
