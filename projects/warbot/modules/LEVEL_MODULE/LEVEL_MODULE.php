<?php
	require_once 'Level.class.php';

	$MODULE_NAME = "LEVEL_MODULE";
	
	bot::loadSQLFile($MODULE_NAME, 'levels');

    //Level Infos
	bot::command("", "$MODULE_NAME/level.php", "pvp", "all", "Show level ranges");
	bot::command("", "$MODULE_NAME/level.php", "level", "all", "Show level ranges");
	bot::command("", "$MODULE_NAME/level.php", "lvl", "all", "Show level ranges");
	bot::regGroup("lvlrng", $MODULE_NAME, "Show level ranges", "lvl", "level");

	//Help files
    bot::help($MODULE_NAME, "level", "level.txt", "guild", "How to use level");
?>
