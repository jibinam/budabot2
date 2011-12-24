<?php
	$MODULE_NAME = "NANO_MODULE";

	//Search for Database Updates
	DB::loadSQLFile($MODULE_NAME, "nanos");
	DB::loadSQLFile($MODULE_NAME, "nanolines");
	DB::loadSQLFile($MODULE_NAME, "nanolines_ref");

	//nano Search
	Command::register($MODULE_NAME, "", "nano.php", "nano", "all", "Searches for a nano and tells you were to get it.");

	//nanolines
	Command::register($MODULE_NAME, "", "nanolines.php", "nanolines", "all", "Shows a list of professions to choose from");
	Command::register($MODULE_NAME, "", "nlprof.php", "nlprof", "all", "Shows a list of nanolines given a profession");
	Command::register($MODULE_NAME, "", "nlline.php", "nlline", "all", "Shows a list of nanos given a nanoline");

	//Settings
	Setting::add($MODULE_NAME, 'maxnano', 'Number of Nanos shown on the list', 'edit', "number", '40', '30;40;50;60', "", "mod");
	Setting::add($MODULE_NAME, "shownanolineicons", "Show icons for the nanolines", "edit", "options", "0", "true;false", "1;0");

	//Helpfiles
	Help::register($MODULE_NAME, "nano", "nano.txt", "guild", "How to search for a nano.");
	Help::register($MODULE_NAME, "nanolines", "nanolines.txt", "all", "How to use nanolines");
	Help::register($MODULE_NAME, "nanolines", "nanolines.txt", "all", "How to use nanolines");
	Help::register($MODULE_NAME, "maxnano", "maxnano.txt", "mod", "Set the max number of nano results");
?>