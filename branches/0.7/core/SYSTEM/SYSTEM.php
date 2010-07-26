<?php 
	$MODULE_NAME = "SYSTEM";
	$PLUGIN_VERSION = 0.1;

	//Load extended messages
	$this->loadSQLFile($MODULE_NAME, "mmdb");

	//Commands
	Command::register("msg", $MODULE_NAME, "plugins.php", "newplugins", ADMIN);
	Command::register("priv", $MODULE_NAME, "plugins.php", "newplugins", ADMIN);
	Command::register("guild", $MODULE_NAME, "plugins.php", "newplugins", ADMIN);
	
	Command::register("msg", $MODULE_NAME, "reboot.php", "reboot", ADMIN);
	Command::register("priv", $MODULE_NAME, "reboot.php", "reboot", ADMIN);
	Command::register("guild", $MODULE_NAME, "reboot.php", "reboot", ADMIN);	
	
	Command::register("msg", $MODULE_NAME, "shutdown.php", "shutdown", ADMIN);
	Command::register("priv", $MODULE_NAME, "shutdown.php", "shutdown", ADMIN);
	Command::register("guild", $MODULE_NAME, "shutdown.php", "shutdown", ADMIN);
	
	Command::register("msg", $MODULE_NAME, "uptime.php", "uptime", MEMBER);
	Command::register("priv", $MODULE_NAME, "uptime.php", "uptime", MEMBER);
	Command::register("guild", $MODULE_NAME, "uptime.php", "uptime", MEMBER);

	//Help Files
	Help::register("systemhelp", $MODULE_NAME, "system.txt", ADMIN, "Admin System Help file.");
?>