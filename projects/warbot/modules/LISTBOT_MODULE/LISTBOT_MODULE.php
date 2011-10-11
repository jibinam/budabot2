<?php
	$MODULE_NAME = "LISTBOT_MODULE";

	//Commands
	bot::command("", "$MODULE_NAME/waitlist.php", "waitlist", "all", "Show/Add the Waitlist");
	
	//Setup
	bot::event($MODULE_NAME, "setup", "setup.php");

	//Helpfile
    bot::help($MODULE_NAME, "waitlist", "waitlist.txt", "guild", "How to use the ListBot", "Listbot");
?>