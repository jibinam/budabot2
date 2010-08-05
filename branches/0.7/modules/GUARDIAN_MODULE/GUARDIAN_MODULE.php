<?php
	$MODULE_NAME = "GUARDIAN_MODULE";

	//Guardian module
	Event::register("leavePriv", $MODULE_NAME, "left_chat.php", "guard", "Remove player who leaves chat from guardian list if he was on it");
	Event::register("joinPriv", $MODULE_NAME, "joined_chat.php", "guard", "Add player to guardian list when he joins chat if he should be on it (Soldier)");
	Event::register("2sec", $MODULE_NAME, "guard_check.php", "guard", "Timer check for guardian list");
	Command::register("", $MODULE_NAME, "guard_order.php", "guard", LEADER, "Show Guardian Order");
	Command::register("", $MODULE_NAME, "cast_guard.php", "g", ALL, "Show Guardian Cast");

	Settings::add("guard_max", $MODULE_NAME, "Max Persons that are shown on Guard list", "edit", "10", "10;15;20;25;30", '0', MODERATOR, "guard_help.txt");

	//Help files
	Help::register("guard", $MODULE_NAME, "guard.txt", ALL, "Guardian Macro and List", "Guardian and Bior R. Commands");
?>