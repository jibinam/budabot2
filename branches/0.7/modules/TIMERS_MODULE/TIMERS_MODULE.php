<?php
	$MODULE_NAME = "TIMERS_MODULE";

	Event::register("setup", $MODULE_NAME, "setup.php");

	// Timer Module
	Command::register("", $MODULE_NAME, "timers.php", "timer", GUILDMEMBER, "Set Personal Timers");
	Command::register("", $MODULE_NAME, "timers.php", "timers", GUILDMEMBER, "Shows running Timers");
	Command::register("", $MODULE_NAME, "countdown.php", "countdown", GUILDMEMBER, "Set a countdown");
	Command::register("", $MODULE_NAME, "countdown.php", "cd", GUILDMEMBER, "Set a countdown");

	Event::register("2sec", $MODULE_NAME, "timers_check.php", "timer");
	
	Settings::add("timers_window", $MODULE_NAME, "Show running timers in a window or directly", "edit", "1", "window only;chat only;window after 3;window after 4;window after 5", '1;2;3;4;5', MODERATOR);

	//Help files
	Help::register("Timer", $MODULE_NAME, "timer.txt", GUILDMEMBER, "Set/Show Timers.");
?>