<?php
	$MODULE_NAME = "ORGLIST_MODULE";
 
	// Checks who in an org is online
	Command::register($MODULE_NAME, "orglist.php", "orglist", MODERATOR, "Check someones org roster");
	Command::register($MODULE_NAME, "orglist.php", "onlineorg", MODERATOR, "Check someones org roster");
	Event::register("logOn", $MODULE_NAME, "orglist.php", "Gets online status of org members");
	Event::register("logOff", $MODULE_NAME, "orglist.php", "Gets offline status of org members");
	
	// Checks if a player is online
	Command::register($MODULE_NAME, "is_online.php", "is", ALL, "Checks if a player is online");
	Event::register("logOn", $MODULE_NAME, "is_online.php", "Gets online status of player");
	Event::register("logOff", $MODULE_NAME, "is_online.php", "Gets offline status of player");

	// Help files
	Help::register($MODULE_NAME, "orglist.txt", "orglist", MODERATOR, "See who is online from someones org.");
	Help::register($MODULE_NAME, "isonline.txt", "IsOnline", ALL, "Checking if a player is online");
?>
