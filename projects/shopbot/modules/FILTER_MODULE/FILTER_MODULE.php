<?php
	DB::loadSQLFile($MODULE_NAME, 'filtercontent');
	DB::loadSQLFile($MODULE_NAME, 'filterplayer');

	//adds a regex string to filter messages by
	Command::register($MODULE_NAME, "", "filter.php", "filter", "admin", "filters messages by content");
	
	Help::register($MODULE_NAME, "filter", "filter.txt", "all", "How to filter messages from being relayed to chat");
?>