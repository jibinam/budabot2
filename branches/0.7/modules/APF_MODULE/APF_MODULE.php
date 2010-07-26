<?php
	$MODULE_NAME = "APF_MODULE";

	//Loottable for the different APF Sectors
	Command::register("", $MODULE_NAME, "loottable.php", "loottable", ALL, "Shows what drops of APF Boss");

	//Guides for the different APF items
	Command::register("", $MODULE_NAME, "tradeskill_loot.php", "guide", ALL, "Shows what to make from apf items");

	//Help files
	Help::register("apf_loot", $MODULE_NAME, "apfloot.txt", GUILDMEMBER, "Show the Loots of the APF");
?>