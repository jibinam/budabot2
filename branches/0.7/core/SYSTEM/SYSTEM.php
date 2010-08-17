<?php 
	$MODULE_NAME = "SYSTEM";

	//Load extended messages
	DB::loadSQLFile($MODULE_NAME, "mmdb");

	//Commands
	Command::register($MODULE_NAME, "plugins.php", "newplugins", ADMIN, 'reloads plugins', 1);
	Command::register($MODULE_NAME, "reboot.php", "reboot", ADMIN, 'reboots the bot', 1);
	Command::register($MODULE_NAME, "shutdown.php", "shutdown", ADMIN, 'shuts the bot down', 1);
	Command::register($MODULE_NAME, "uptime.php", "uptime", MEMBER, 'shows how long the bot has been online', 1);
	Command::register($MODULE_NAME, "memory.php", "memory", MEMBER, 'shows the memory usage of the bot', 1);
	Command::register($MODULE_NAME, "cmdlist.php", "cmdlist", MEMBER, 'shows the list of commands', 1);
	Command::register($MODULE_NAME, "eventlist.php", "eventlist", MEMBER, 'shows the list of events', 1);

	//Help Files
	Help::register($MODULE_NAME, "system.txt", "system", ADMIN, "Admin System Help file.");
?>