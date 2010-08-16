<?php
	$MODULE_NAME = "DBLOOT_MODULE";

	// DB loot manager
	Command::register($MODULE_NAME, "dbloot.php", "dbloot", LEADER, "Used to add DB loot to the loot list");
	Command::register($MODULE_NAME, "dbloot.php", "db2loot", LEADER, "Used to add DB loot to the loot list");
	Command::register($MODULE_NAME, "dbloot.php", "db1", LEADER, "Shows Possible DB1 Armor/NCUs/Programs");
	Command::register($MODULE_NAME, "dbloot.php", "db2", LEADER, "Shows Possible DB2 Armor");

	//Helpfiles
	Help::register("dbloot", $MODULE_NAME, "dbloot.txt", ALL, "Loot manager for DB1/DB2 Instance");

?>
