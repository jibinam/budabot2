<?php
	$MODULE_NAME = "POCKETBOSS_MODULE";

	//Setup
	DB::loadSQLFile($MODULE_NAME, "pocketboss");

	//Pocketboss module
	Command::register($MODULE_NAME, "pocketboss.php", "pb", ALL, "Shows what symbs a PB drops");
	Command::register($MODULE_NAME, "pocketboss.php", "symb", ALL, "Shows what PB drops a symb");

	//Helpiles
    Help::register("pocketboss", $MODULE_NAME, "pocketboss.txt", ALL, "See what drops which Pocketboss");
?>