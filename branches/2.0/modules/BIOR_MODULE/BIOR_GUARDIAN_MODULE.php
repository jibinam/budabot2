<?php
	$MODULE_NAME = "BIOR_MODULE";

	//Bio Regrowth module
	Event::register("leavePriv", $MODULE_NAME, "bior_left_chat.php", "Remove player who leaves chat from bior list if he was on it");
	Event::register("joinPriv", $MODULE_NAME, "bior_joined_chat.php", "Add player to bior list when he joins chat if he should be on it (Keep,Adv,Enf,Eng)");
	Event::register("2sec", $MODULE_NAME, "bior_check.php", "Timer check for bior list");
	
	Command::register($MODULE_NAME, "bior_order.php", "bior", LEADER, "Show Bio Regrowth Order");
	Command::register($MODULE_NAME, "cast_bior.php", "b", ALL, "Show Bio Regrowth Cast");
	
	Settings::add("bior_max", $MODULE_NAME, "Max Persons that are shown on BioR list", "edit", "10", "10;15;20;25;30", '0', MODERATOR, "bior_help.txt");

	//Help files
	Help::register($MODULE_NAME, "bior.txt", "bior", ALL, "Bio Regrowth Macro and List");
	
	//Guardian module
	Event::register("leavePriv", $MODULE_NAME, "guardian_left_chat.php", "Remove player who leaves chat from guardian list if he was on it");
	Event::register("joinPriv", $MODULE_NAME, "guardian_joined_chat.php", "Add player to guardian list when he joins chat if he should be on it (Soldier)");
	Event::register("2sec", $MODULE_NAME, "guard_check.php", "Timer check for guardian list");
	
	Command::register($MODULE_NAME, "guard_order.php", "guard", LEADER, "Show Guardian Order");
	Command::register($MODULE_NAME, "cast_guard.php", "g", ALL, "Show Guardian Cast");

	Settings::add("guard_max", $MODULE_NAME, "Max Persons that are shown on Guard list", "edit", "10", "10;15;20;25;30", '0', MODERATOR, "guard_help.txt");

	//Help files
	Help::register($MODULE_NAME, "guard.txt", "guard", ALL, "Guardian Macro and List");
?>