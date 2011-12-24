<?php
	$MODULE_NAME = "QUOTE_MODULE";

	//Setup
	Event::register($MODULE_NAME, "setup", "setup.php");
	Event::register($MODULE_NAME, "24hrs", "quotestats.php", "none", "Update Quote Stats");

	//Commands
	Command::register($MODULE_NAME, "", "quotestats.php", "quoteupdate", "guildadmin", "Update Quote Stats");
	Command::register($MODULE_NAME, "", "quote.php", "quote", "all", "Add/Remove/View Quotes");

	Setting::add($MODULE_NAME, "quote_add_min", "Minimum org level needed to add quote.", "edit", "number", "-1", "Anyone;At least in Private chat;0;1;2;3;4;5;6", "-2;-1;0;1;2;3;4;5;6", "mod");
	Setting::add($MODULE_NAME, "quote_stat_count", "Number of users shown in stats.", "edit", "number", "10");

	//Help files
	Help::register($MODULE_NAME, "quote", "quote.txt", "all", "Add/Remove/View Quotes");
?>