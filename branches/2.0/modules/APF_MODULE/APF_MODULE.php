<?php
	$MODULE_NAME = "APF_MODULE";

	//Loottable for the different APF Sectors
	Command::register($MODULE_NAME, "loottable.php", "loottable", ALL, "Shows what drops of APF Boss");

	//Guides for the different APF items
	Command::register($MODULE_NAME, "apfloot.php", "apfloot", ALL, "Shows what to make from apf items");

	//Help files
	Help::register($MODULE_NAME, "apfloot.txt", "apfloot", GUILDMEMBER, "Show the Loots of the APF");
?>