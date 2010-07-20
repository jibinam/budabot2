<?php
	$MODULE_NAME = "GUILD_MANAGEMENT_MODULE";

	//Check Inactives
	$this->command("msg", $MODULE_NAME, "inactive_mem.php", "inactivemem", ADMIN, "Check for inactive members");

	//Help files
    Help::register("inactivemem", $MODULE_NAME, "manage_guild.txt", ADMIN, "Help on Checking for Inactive Members");
 
?>
