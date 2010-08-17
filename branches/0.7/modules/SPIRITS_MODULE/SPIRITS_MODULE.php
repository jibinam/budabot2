<?php
	$MODULE_NAME = "SPIRITS_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, "spirits");
	
	Command::register($MODULE_NAME, "spirits.php", "spirits", ALL, "Search for Spirits");
	Command::register($MODULE_NAME, "spirits.php", "spiritslvl", ALL, "Search for Spirits");
	Command::register($MODULE_NAME, "spirits.php", "spiritsagi", ALL, "Search for Spirits");
	Command::register($MODULE_NAME, "spirits.php", "spiritssen", ALL, "Search for Spirits");
	
	Help::register($MODULE_NAME, "spirits.txt", "spirits", ALL, "Search for Spirits");
	
?>