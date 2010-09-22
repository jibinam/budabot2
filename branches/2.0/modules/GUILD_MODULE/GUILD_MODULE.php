<?php
	$MODULE_NAME = "GUILD_MODULE";

	//Setup of the Basic Guild Modules
	Event::register("setup", $MODULE_NAME, "setup.php", '', 1);

    // Org Roster list creation and Notify on/off handling
	Event::register("24hrs", $MODULE_NAME, "roster_guild.php", 'Update the org roster from FC servers', 1);
	Event::register("orgmsg", $MODULE_NAME, "notify_auto.php", 'Add or remove user if they join or leave or are kicked from org', 1);

	Command::register($MODULE_NAME, "notify.php", "notify", MODERATOR, "Adding a member man. to the notify list", 1);
	Command::register($MODULE_NAME, "inactive_mem.php", "inactivemem", ADMIN, "Check for inactive members");

	Settings::add("bot_notify", $MODULE_NAME, "Show/Hide Logoffs in Org Chat (Spam Prevention)", "edit", "1", "Show Logoffs;Hide Logoffs", '1;0', MODERATOR, "botnotify.txt", 1);
	
	//Helpfile
    Help::register($MODULE_NAME, "notify.txt", "notify", MODERATOR, "Add or remove a player from the notify list.");
	Help::register($MODULE_NAME, "manage_guild.txt", "inactivemem", ADMIN, "Help on Checking for Inactive Members");
?>