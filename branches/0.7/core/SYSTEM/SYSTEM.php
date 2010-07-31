<?php 
	$MODULE_NAME = "SYSTEM";

	//Load extended messages
	$this->loadSQLFile($MODULE_NAME, "mmdb");

	//Commands
	Command::register("", $MODULE_NAME, "plugins.php", "newplugins", ADMIN);
	Command::register("", $MODULE_NAME, "reboot.php", "reboot", ADMIN);
	Command::register("", $MODULE_NAME, "shutdown.php", "shutdown", ADMIN);
	Command::register("", $MODULE_NAME, "uptime.php", "uptime", MEMBER);
	Command::register("", $MODULE_NAME, "memory.php", "memory", MEMBER);
	Command::register("", $MODULE_NAME, "cmdlist.php", "cmdlist", MEMBER);
	Command::register("", $MODULE_NAME, "eventlist.php", "eventlist", MEMBER);

	//Help Files
	Help::register("systemhelp", $MODULE_NAME, "system.txt", ADMIN, "Admin System Help file.");
?>