<?php
	require_once 'db_utils.php';

	$MODULE_NAME = "EXTRAHELPBOT_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, "dyna");
 
	Command::register($MODULE_NAME, "mobloot.php", "mobloot", ALL, "loot QL Infos ");
	Command::register($MODULE_NAME, "random.php", "random", ALL, "Random order");
	Command::register($MODULE_NAME, "cluster.php", "cluster", ALL, "cluster location");
	Command::register($MODULE_NAME, "buffitem.php", "buffitem", ALL, "buffitem look up");
	Command::register($MODULE_NAME, "whatbuffs.php", "whatbuffs", ALL, "find items that buff");
	Command::register($MODULE_NAME, "dyna.php", "dyna", ALL, "Search for RK Dynaboss");
	
	Help::register($MODULE_NAME, "dyna.txt", "dyna", ALL, "Search for RK Dynaboss");
?>
