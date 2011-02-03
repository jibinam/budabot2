<?php
	$MODULE_NAME = "ORGLIST_MODULE";
 
	// Checks who in an org is online
	bot::command("", "$MODULE_NAME/orglist.php", "orglist", "guildadmin", "Check someones org roster");
	bot::command("", "$MODULE_NAME/orglist.php", "onlineorg", "guildadmin", "Check someones org roster");

	Event::register($MODULE_NAME, "logOn", "orglist.php", "none", "Gets online status of org members");
	Event::register($MODULE_NAME, "logOff", "orglist.php", "none", "Gets offline status of org members");
	
	// Checks if a player is online
	bot::command("", "$MODULE_NAME/is_online.php", "is", "all", "Checks if a player is online");

	Event::register($MODULE_NAME, "logOn", "is_online.php", "none", "Gets online status of player");
	Event::register($MODULE_NAME, "logOff", "is_online.php", "none", "Gets offline status of player");

	// Help files
	bot::help($MODULE_NAME, "orglist", "orglist.txt", "all", "See who is online from someones org.");
	bot::help($MODULE_NAME, "is", "isonline.txt", "guild", "Checking if a player is online");
?>
