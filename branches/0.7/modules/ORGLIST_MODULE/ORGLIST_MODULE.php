<?php
	$MODULE_NAME = "ORGLIST_MODULE";
 
	// Checks who in an org is online
	Command::register("", $MODULE_NAME, "orglist.php", "orglist", MODERATOR, "Check someones org roster");
	Command::register("", $MODULE_NAME, "orglist.php", "onlineorg", MODERATOR, "Check someones org roster");
	Event::register("logOn", $MODULE_NAME, "orglist.php", "orglist");
	Event::register("logOff", $MODULE_NAME, "orglist.php", "orglist");
	
	// Checks if a player is online
	Command::register("", $MODULE_NAME, "is_online.php", "is", ALL, "Checks if a player is online");
	Event::register("logOn", $MODULE_NAME, "is_online.php", "is");
	Event::register("logOff", $MODULE_NAME, "is_online.php", "is");

	// Help files
	Help::register("orglist", $MODULE_NAME, "orglist.txt", MODERATOR, "See who is online from someones org.");
	Help::register("IsOnline", $MODULE_NAME, "isonline.txt", ALL, "Checking if a player is online");
?>
