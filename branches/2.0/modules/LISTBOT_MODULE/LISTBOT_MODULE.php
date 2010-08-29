<?php
	$MODULE_NAME = "LISTBOT_MODULE";
	
	//Setup
	Event::register("setup", $MODULE_NAME, "setup.php");

	//Commands
	Command::register($MODULE_NAME, "waitlist.php", "waitlist", ALL, "Show/Add the Waitlist");

	//Helpfile
    Help::register($MODULE_NAME, "waitlist.txt", "waitlist", ALL, "How to use the ListBot");
?>