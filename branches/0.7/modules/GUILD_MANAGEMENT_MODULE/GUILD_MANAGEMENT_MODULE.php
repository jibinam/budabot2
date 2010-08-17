<?php
	$MODULE_NAME = "GUILD_MANAGEMENT_MODULE";

	//Check Inactives
	Command::register($MODULE_NAME, "inactive_mem.php", "inactivemem", ADMIN, "Check for inactive members");

	//Help files
    Help::register($MODULE_NAME, "manage_guild.txt", "inactivemem", ADMIN, "Help on Checking for Inactive Members");
 
?>
