<?php
	$MODULE_NAME = "NANO_MODULE";

	//Search for Database Updates
	DB::loadSQLFile($MODULE_NAME, "nanos");

    //nano Search
	Command::register("", $MODULE_NAME, "nano.php", "nano", ALL, "Searches for a nano and tells you were to get it.");

	//Settings
    Settings::add('maxnano', $MODULE_NAME, 'Number of Nanos shown on the list', 'edit', '40', '30;40;50;60', "0", MODERATOR, "maxnano_help.txt");

	//Help files
    Help::register("nano", $MODULE_NAME, "nano.txt", ALL, "How to search for a nano."); 
?>