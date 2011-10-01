<?php
	DB::add_table_replace('#__quote', 'quote');
	DB::loadSQLFile($MODULE_NAME, "quote");

	//Commands
	Command::register($MODULE_NAME, "", "quote.php", "quote", "guild", "Add/Remove/View Quotes");

	Setting::add($MODULE_NAME, "quote_stat_count", "Number of users shown in stats", "edit", "number", "10");

	//Help files
	Help::register($MODULE_NAME, "quote", "quote.txt", "guild", "How to add/remove/view quotes");
?>