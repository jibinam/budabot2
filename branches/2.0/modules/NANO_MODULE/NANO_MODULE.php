<?php
	$MODULE_NAME = "NANO_MODULE";

	//Search for Database Updates
	DB::loadSQLFile($MODULE_NAME, "nanos");
	DB::loadSQLFile($MODULE_NAME, "nanolines");

    //nano Search
	Command::register($MODULE_NAME, "nano.php", "nano", ALL, "Searches for a nano and tells you were to get it.");
	
	//nanolines
	Command::register($MODULE_NAME, "nanolines.php", "nanolines", ALL, "Shows a list of professions to choose from");
	Command::register($MODULE_NAME, "nlprof.php", "nlprof", ALL, "Shows a list of nanolines given a profession");
	Command::register($MODULE_NAME, "nlline.php", "nlline", ALL, "Shows a list of nanos given a nanoline");

	//Settings
    Settings::add('maxnano', $MODULE_NAME, 'Number of Nanos shown on the list', 'edit', '40', '30;40;50;60', "0", MODERATOR, "maxnano_help.txt");
	Settings::add("shownanolineicons", $MODULE_NAME, "Show icons for the nanolines", "edit", "0", "true;false", "1;0");

	//Help files
    Help::register($MODULE_NAME, "nano.txt", "nano", ALL, "How to search for a nano.");
	Help::register($MODULE_NAME, "nanolines.txt", "nanolines", ALL, "How to use nanolines");
?>