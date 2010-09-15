<?php
	require_once 'db_utils.php';
	require_once 'trickle_functions.php';

	$MODULE_NAME = "EXTRAHELPBOT_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, "dyna");
	DB::loadSQLFile($MODULE_NAME, "research");
	DB::loadSQLFile($MODULE_NAME, "trickle");
 
	Command::register($MODULE_NAME, "mobloot.php", "mobloot", ALL, "loot QL Infos ");
	Command::register($MODULE_NAME, "random.php", "random", ALL, "Random order");
	Command::register($MODULE_NAME, "cluster.php", "cluster", ALL, "cluster location");
	Command::register($MODULE_NAME, "buffitem.php", "buffitem", ALL, "buffitem look up");
	Command::register($MODULE_NAME, "whatbuffs.php", "whatbuffs", ALL, "find items that buff");
	Command::register($MODULE_NAME, "dyna.php", "dyna", ALL, "Search for RK Dynaboss");
	Command::register($MODULE_NAME, "research.php", "research", ALL, "Info on Research");
	
	//Max XP calculator
	Command::register($MODULE_NAME, "cap_xp.php", "capsk", ALL, "Max SK Calculator");
	Command::register($MODULE_NAME, "cap_xp.php", "capxp", ALL, "Max XP Calculator");
	
	//trickle
	Command::register($MODULE_NAME, "trickle.php", "trickle", ALL, "Shows how much skills you will gain by increasing an ability");
	
	Help::register($MODULE_NAME, "buffitem.txt", "buffitem", ALL, "How to use buffitem");
	Help::register($MODULE_NAME, "cluster.txt", "cluster", ALL, "How to use cluster");
	Help::register($MODULE_NAME, "mobloot.txt", "mobloot", ALL, "How to use mobloot");
	Help::register($MODULE_NAME, "whatbuffs.txt", "whatbuffs", ALL, "How to use whatbuffs");
	Help::register($MODULE_NAME, "dyna.txt", "dyna", ALL, "Search for RK Dynaboss");
	Help::register($MODULE_NAME, "research.txt", "Research", ALL, "Info on Research");
	Help::register($MODULE_NAME, "capxp.txt", "capxp", ALL, "Set your reasearch bar for max xp/sk");
	Help::register($MODULE_NAME, "trickle.txt", "Trickle", ALL, "How to use trickle");
?>
