<?php
	$MODULE_NAME = "LEVEL_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, 'levels');

    //Level Infos
	Command::register("", $MODULE_NAME, "level.php", "pvp", ALL, "Show level ranges");
	Command::register("", $MODULE_NAME, "level.php", "level", ALL, "Show level ranges");
	Command::register("", $MODULE_NAME, "level.php", "lvl", ALL, "Show level ranges");

	//Missions
	Command::register("", $MODULE_NAME, "missions.php", "mission", ALL);
	Command::register("", $MODULE_NAME, "missions.php", "missions", ALL);
	
	//XP/SK/AXP Calculator
	Command::register("", $MODULE_NAME, "xp_sk_calc.php", "sk", ALL, "SK Calculator");
	
	Command::register("", $MODULE_NAME, "xp_sk_calc.php", "xp", ALL, "XP Calculator");

	Command::register("", $MODULE_NAME, "axp.php", "axp", ALL, "AXP Calculator");

	//Title Levels
	Command::register("", $MODULE_NAME, "title.php", "title", ALL, "Show the Titlelevels and how much IP/Level");

	//Help files
    Help::register("level", $MODULE_NAME, "level.txt", ALL, "Levelinfos");
    Help::register("title_level", $MODULE_NAME, "title.txt", ALL, "Infos about TitleLevels");
    Help::register("missions", $MODULE_NAME, "missions.txt", ALL, "Who can roll a specific QL of a mission");
	Help::register("experience", $MODULE_NAME, "experience.txt", ALL, "XP/SK/AXP Infos");
?>
