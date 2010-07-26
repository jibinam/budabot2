<?php
	$MODULE_NAME = "QUOTE_MODULE";

	//Setup
	Event::register("setup", $MODULE_NAME, "setup.php");
	Event::register("24hrs", $MODULE_NAME, "quotestats.php", "none", "Update Quote Stats");

	//Commands
	Command::register("", $MODULE_NAME, "quotestats.php", "quoteupdate", MODERATOR, "Update Quote Stats");
	Command::register("", $MODULE_NAME, "quote.php", "quote", ALL, "Add/Remove/View Quotes");

	//Help files
	Help::register("quote", $MODULE_NAME, "quote.txt", ALL, "Add/Remove/View Quotes");

	Settings::add("quote_add_min", $MODULE_NAME, "Minimum org level needed to add quote.", "edit", "-1", "Anyone;At least in Private chat;0;1;2;3;4;5;6", "-2;-1;0;1;2;3;4;5;6", MODERATOR);
	Settings::add("quote_stat_count", $MODULE_NAME, "Number of users shown in stats.", "edit", "10", "number", "0", MODERATOR);
?>