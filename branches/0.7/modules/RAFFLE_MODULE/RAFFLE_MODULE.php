<?php

	$MODULE_NAME = "RAFFLE_MODULE";
	
	//Setup
	Event::register("setup", $MODULE_NAME, "setup.php");

	//raffle message
	Command::register($MODULE_NAME, "raffle.php", "raffle", GUILDMEMBER);
	Command::register($MODULE_NAME, "status.php", "rafflestatus", GUILDMEMBER);
	Command::register($MODULE_NAME, "join.php", "joinRaffle", GUILDMEMBER);
	Command::register($MODULE_NAME, "leave.php", "leaveRaffle", GUILDMEMBER);

	//timer
	Event::register("2sec", $MODULE_NAME, "check_winner.php", "", "Checks to see if raffle is over");

	//Help files
	Help::register("Raffle", $MODULE_NAME, "raffle.txt", GUILDMEMBER, "Start/Join/Leave Raffles");

	//Settings
	Settings::add("defaultraffletime", $MODULE_NAME, "Sets how long the raffle should go for in minutes.", "edit", 3, "number");

?>