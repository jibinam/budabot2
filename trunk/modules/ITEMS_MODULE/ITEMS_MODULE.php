<?php
	require_once 'functions.php';

	//Load items db
	DB::loadSQLFile($MODULE_NAME, "aodb");

	//Items Search
	Command::register($MODULE_NAME, "", "items.php", "items", "all", "Search for an item");

	Command::register($MODULE_NAME, "", "updateitems.php", "updateitems", "guild", "Download the latest version of the items db");
	
	Event::register($MODULE_NAME, "24hrs", "itemsdb_check.php", "Check to make sure items db is the latest version available");

	//Settings
	Setting::add($MODULE_NAME, 'maxitems', 'Number of Items shown on the list', 'edit', "number", '40', '30;40;50;60', "", "mod");

	//Help files
	Help::register($MODULE_NAME, "items", "items.txt", "guild", "How to search for an item.");
	Help::register($MODULE_NAME, "updateitems", "updateitems.txt", "guild", "How to update your local items database");
?>