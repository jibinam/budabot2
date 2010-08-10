<?php
	$MODULE_NAME = "ITEMS_MODULE";

	//Load items db
	DB::loadSQLFile($MODULE_NAME, "aodb");
	
    //Items Search
	Command::register($MODULE_NAME, "items.php", "items", ALL, "Searches for an item in the Database");

	//Settings
    Settings::add('maxitems', $MODULE_NAME, 'Number of Items shown on the list', 'edit', '40', '30;40;50;60', "0", MODERATOR, "aodb_maxitems_help.txt");

	//Help files
    Help::register("items", $MODULE_NAME, "items.txt", ALL, "How to search for an item."); 
?>