<?php
	$MODULE_NAME = "GUILD_MODULE";

	//Setup of the Basic Guild Modules
	bot::regevent("setup", "$MODULE_NAME/setup.php");
	
	//Verifies the Onlinelist every hour
	bot::event("1hour", "$MODULE_NAME/online_check.php", "online", "Online check");
	
	// Logon Handling
	bot::command("", "$MODULE_NAME/logon_msg.php", "logon", "all", "Sets a Logon Msg");

    // Org Roster list creation and Notify on/off handling
	bot::regevent("24hrs", "$MODULE_NAME/roster_guild.php");
	bot::regevent("orgmsg", "$MODULE_NAME/notify_auto.php");
    bot::regevent("logOn", "$MODULE_NAME/notify_auto.php");
    bot::regevent("logOff", "$MODULE_NAME/notify_auto.php");
	bot::command("guild", "$MODULE_NAME/notify.php", "notify", "mod", "Adding a member man. to the notify list");
	bot::command("msg", "$MODULE_NAME/notify.php", "notify", "mod", "Adding a member man. to the notify list");
	bot::command("priv", "$MODULE_NAME/notify.php", "notify", "mod", "Adding a member man. to the notify list");
	
	bot::command("", "$MODULE_NAME/inactive_mem.php", "inactivemem", "admin", "Check for inactive members");
	bot::command("", "$MODULE_NAME/updateorg.php", "updateorg", "mod", "Forcing an update of the org roster");
	
	// Show orgmembers
	bot::command("", "$MODULE_NAME/orgmembers.php", "orgmembers", "all", "Show the Members(sorted by name) of the org");
	bot::command("", "$MODULE_NAME/orgranks.php", "orgranks", "all", "Show the Members(sorted by rank) of the org");

	bot::addsetting("bot_notify", "Show/Hide Logoffs in Org Chat (Spam Prevention)", "edit", "1", "Show Logoffs;Hide Logoffs", '1;0', "mod", "$MODULE_NAME/botnotify.txt");
	
	//Helpfile
    bot::help("notify", "$MODULE_NAME/notify.txt", "mod", "Add or remove a player from the notify list.");
	bot::help("inactivemem", "$MODULE_NAME/manage_guild.txt", "all", "Help on Checking for Inactive Members");
	bot::help("updateorg", "$MODULE_NAME/updateorg.txt", "mod", "Force an update of org roster");
	bot::help("logonmsg", "$MODULE_NAME/logonmsg.txt", "guild", "Changing your logon message");
	bot::help("orgmembers", "$MODULE_NAME/orgmembers_orgranks.txt", "guild", "Show current OrgMembers");
?>