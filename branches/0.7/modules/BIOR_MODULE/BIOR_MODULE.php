<?php
	$MODULE_NAME = "BIOR_MODULE";

	//Bio Regrowth module
	Event::register("leavePriv", $MODULE_NAME, "left_chat.php", "bior", "Remove player who leaves chat from bior list if he was on it");
	Event::register("joinPriv", $MODULE_NAME, "joined_chat.php", "bior", "Add player to bior list when he joins chat if he should be on it (Keep,Adv,Enf,Eng)");
	Event::register("2sec", $MODULE_NAME, "bior_check.php", "bior", "Timer check for bior list");
	
	Command::register("", $MODULE_NAME, "bior_order.php", "bior", LEADER, "Show Bio Regrowth Order");
	Command::register("", $MODULE_NAME, "cast_bior.php", "b", ALL, "Show Bio Regrowth Cast");
	
	Settings::add("bior_max", $MODULE_NAME, "Max Persons that are shown on BioR list", "edit", "10", "10;15;20;25;30", '0', MODERATOR, "bior_help.txt");

	//Help files
	Help::register("bior", $MODULE_NAME, "bior.txt", ALL, "Bio Regrowth Macro and List");
?>