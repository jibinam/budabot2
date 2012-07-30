<?php
	$event->register($MODULE_NAME, "leavePriv", "bior_left_chat.php", "Remove player who leaves chat from bior list if he was on it");
	$event->register($MODULE_NAME, "joinPriv", "bior_joined_chat.php", "Add player to bior list when he joins chat if he should be on it (Keep,Adv,Enf,Eng)");
	$event->register($MODULE_NAME, "2sec", "bior_check.php", "Timer check for bior list");
	$event->register($MODULE_NAME, "leavePriv", "guardian_left_chat.php", "Remove player who leaves chat from guardian list if he was on it");
	$event->register($MODULE_NAME, "joinPriv", "guardian_joined_chat.php", "Add player to guardian list when he joins chat if he should be on it (Soldier)");
	$event->register($MODULE_NAME, "2sec", "guard_check.php", "Timer check for guardian list");
	
	$command->register($MODULE_NAME, "", "bior.php", "bior", "rl", "Show Bio Regrowth Order", "bior.txt");
	$command->register($MODULE_NAME, "", "cast_bior.php", "b", "all", "Show Bio Regrowth Cast", "bior.txt");
	$command->register($MODULE_NAME, "", "guard.php", "guard", "rl", "Show Guardian Order", "guard.txt");
	$command->register($MODULE_NAME, "", "cast_guard.php", "g", "all", "Show Guardian Cast", "guard.txt");
	
	$setting->add($MODULE_NAME, "bior_max", "Max Persons that are shown on BioR list", "edit", "number", "10", "10;15;20;25;30", '', "mod", "bior_max.txt");
	$setting->add($MODULE_NAME, "guard_max", "Max Persons that are shown on Guard list", "edit", "number", "10", "10;15;20;25;30", '', "mod", "guard_max.txt");
?>