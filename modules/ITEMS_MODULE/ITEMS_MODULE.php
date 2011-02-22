<?php
	require_once 'functions.php';

	$MODULE_NAME = "ITEMS_MODULE";

	//Load items db
	DB::loadSQLFile($MODULE_NAME, "aodb");

	//Items Search
	Command::register($MODULE_NAME, "", "items.php", "items", "all", "Searches for an item in the Database");

	Command::register($MODULE_NAME, "", "updateitems.php", "updateitems", "guild", "Download the latest version of the items db");

	//Settings
	Setting::add($MODULE_NAME, 'maxitems', 'Number of Items shown on the list', 'edit', "number", '40', '30;40;50;60', "", "mod", "maxitems");
	Setting::add($MODULE_NAME, 'itemdb_location', 'Where to search for items', 'edit', "options", 'local', 'local;Xyphos.com', "", "mod");

	//Help files
	Help::register($MODULE_NAME, "items", "items.txt", "guild", "How to search for an item.");
	Help::register($MODULE_NAME, "updateitems", "updateitems.txt", "guild", "How to update your local items database");
	Help::register($MODULE_NAME, "maxitems", "maxitems.txt", "mod", "Set the max number of item results");
?>