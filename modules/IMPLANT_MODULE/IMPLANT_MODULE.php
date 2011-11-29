<?php
	require_once 'implant_functions.php';

	// Setup
	DB::loadSQLFile($MODULE_NAME, "implant_requirements");
	DB::loadSQLFile($MODULE_NAME, "premade_implant");

	// Private
	Command::register($MODULE_NAME, "", "implant.php", "implant", "all", "Shows info about implants given a ql or stats");
	Command::register($MODULE_NAME, "", "premade.php", "premade", "all", "Searches for implants out of the premade implants booths");
	Command::register($MODULE_NAME, "", "cluster.php", "cluster", "all", "cluster location");

	// Help
	Help::register($MODULE_NAME, "implant", "implant.txt", "all", "How to find implant stats given ql or player stats");
	Help::register($MODULE_NAME, "premade", "premade_implant.txt", "guild", "How to search for premade implants");
	Help::register($MODULE_NAME, "cluster", "cluster.txt", "all", "How to use cluster");
?>