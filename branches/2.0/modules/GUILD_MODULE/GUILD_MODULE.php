<?php
	$MODULE_NAME = "GUILD_MODULE";

	//Setup of the Basic Guild Modules
	Event::register("setup", $MODULE_NAME, "setup.php", '', 1);
	
	//Verifies the Onlinelist every 1hour
	Event::register("1hour", $MODULE_NAME, "online_check.php", "Online check");
	
	// Logon Handling
	Command::register($MODULE_NAME, "logon_msg.php", "logon", ALL, "Sets a Logon Msg");

    // Org Roster list creation and Notify on/off handling
	Event::register("24hrs", $MODULE_NAME, "roster_guild.php", 'Update the org roster from FC servers', 1);
	Event::register("orgmsg", $MODULE_NAME, "notify_auto.php", 'Add or remove user if they join or leave or are kicked from org', 1);

	Command::register($MODULE_NAME, "notify.php", "notify", MODERATOR, "Adding a member man. to the notify list", 1);
	Command::register($MODULE_NAME, "inactive_mem.php", "inactivemem", ADMIN, "Check for inactive members");
	Command::register($MODULE_NAME, "updateorg.php", "updateorg", MODERATOR, "Forcing an update of the org roster");
	
	// Show orgmembers
	Command::register($MODULE_NAME, "orgmembers.php", "orgmembers", ALL, "Show the Members(sorted by name) of the org");
	Command::register($MODULE_NAME, "orgranks.php", "orgranks", ALL, "Show the Members(sorted by rank) of the org");

	Settings::add("bot_notify", $MODULE_NAME, "Show/Hide Logoffs in Org Chat (Spam Prevention)", "edit", "1", "Show Logoffs;Hide Logoffs", '1;0', MODERATOR, "botnotify.txt", 1);
	
	//Helpfile
    Help::register($MODULE_NAME, "notify.txt", "notify", MODERATOR, "Add or remove a player from the notify list.");
	Help::register($MODULE_NAME, "manage_guild.txt", "inactivemem", ADMIN, "Help on Checking for Inactive Members");
	Help::register($MODULE_NAME, "logonmsg.txt", "logonmsg", GUILDMEMBER, "Changing your logon message");
    Help::register($MODULE_NAME, "orgmembers_orgranks.txt", "orgmembers", GUILDMEMBER, "Show current OrgMembers");
	Help::register($MODULE_NAME, "updateorg.txt", "updateorg", MODERATOR, "Force an update of org roster");
?>