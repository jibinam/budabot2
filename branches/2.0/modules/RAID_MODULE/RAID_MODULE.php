<?php
	$MODULE_NAME = "RAID_MODULE";

	//Loot list and adding/removing of players	
	Command::register($MODULE_NAME, "loot.php", "loot", LEADER, "Adds an item to the loot list");
	Command::register($MODULE_NAME, "multiloot.php", "multiloot", LEADER, "Adds items using multiloot");
	Command::register($MODULE_NAME, "loot.php", "clear", LEADER, "Clears the loot list");
	Command::register($MODULE_NAME, "rollloot.php", "flatroll", LEADER, "Rolls the loot list");
	Command::register($MODULE_NAME, "rollloot.php", "rollloot", LEADER, "Rolls the loot list");
	Command::register($MODULE_NAME, "rollloot.php", "result", LEADER, "Rolls the loot list");
	Command::register($MODULE_NAME, "rollloot.php", "win", LEADER, "Rolls the loot list");
	Command::register($MODULE_NAME, "remloot.php", "remloot", LEADER, "Remove item from loot list");
	Command::register($MODULE_NAME, "reroll.php", "reroll", LEADER, "Rerolls the residual loot list");
	Command::register($MODULE_NAME, "13.php", "13", LEADER, "Adds apf13 loot list");
	Command::register($MODULE_NAME, "28.php", "28", LEADER, "Adds apf28 loot list");
	Command::register($MODULE_NAME, "35.php", "35", LEADER, "Adds apf35 loot list");
	
	/* Commands used for both methods */
	//Adding/Removing from loot
	Command::register($MODULE_NAME, "list.php", "list", ALL, "Shows the loot list");
	Command::register($MODULE_NAME, "add.php", "add", ALL, "Let a player adding to a slot");	
	Command::register($MODULE_NAME, "rem.php", "rem", ALL, "Let a player removing from a slot");
	
	//Settings
	Settings::add("add_on_loot", $MODULE_NAME, "Adding to loot show on", "edit", "1", "tells;privatechat;privatechat and tells", '1;2;3', MODERATOR);

	//Help files
	Help::register($MODULE_NAME, "add_rem.txt", "add", ALL, "Adding to a lootitem");
	Help::register($MODULE_NAME, "add_rem.txt", "rem", ALL, "Removing your bid on a lootitem");
	Help::register($MODULE_NAME, "flatroll.txt", "loot", LEADER, "Adding an item to be flatrolled");
	Help::register($MODULE_NAME, "flatroll.txt", "remloot", LEADER, "Removing an item from a flatroll list");
	Help::register($MODULE_NAME, "flatroll.txt", "flatroll", LEADER, "Flatroll an item");
	Help::register($MODULE_NAME, "flatroll.txt", "multiloot", LEADER, "Adding multiple of an item to be rolled");

?>