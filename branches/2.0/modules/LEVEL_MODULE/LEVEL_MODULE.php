<?php
	require_once 'Level.class.php';

	$MODULE_NAME = "LEVEL_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, 'levels');

    //Level Infos
	Command::register($MODULE_NAME, "level.php", "pvp", ALL, "Show level ranges");
	Command::register($MODULE_NAME, "level.php", "level", ALL, "Show level ranges");
	Command::register($MODULE_NAME, "level.php", "lvl", ALL, "Show level ranges");

	//Missions
	Command::register($MODULE_NAME, "missions.php", "mission", ALL);
	Command::register($MODULE_NAME, "missions.php", "missions", ALL);
	
	//XP/SK/AXP Calculator
	Command::register($MODULE_NAME, "xp_sk_calc.php", "sk", ALL, "SK Calculator");
	
	Command::register($MODULE_NAME, "xp_sk_calc.php", "xp", ALL, "XP Calculator");

	Command::register($MODULE_NAME, "axp.php", "axp", ALL, "AXP Calculator");

	//Title Levels
	Command::register($MODULE_NAME, "title.php", "title", ALL, "Show the Titlelevels and how much IP/Level");

	//Help files
    Help::register($MODULE_NAME, "level.txt", "level", ALL, "Levelinfos");
    Help::register($MODULE_NAME, "title.txt", "title_level", ALL, "Infos about TitleLevels");
    Help::register($MODULE_NAME, "missions.txt", "missions", ALL, "Who can roll a specific QL of a mission");
	Help::register($MODULE_NAME, "experience.txt", "experience", ALL, "XP/SK/AXP Infos");
?>
