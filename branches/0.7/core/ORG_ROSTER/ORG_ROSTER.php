<?php
	$MODULE_NAME = "ORG_ROSTER";

	//Setup of the Basic Guild Modules
	Event::register("setup", $MODULE_NAME, "setup.php");

    // Org Roster list creation and Notify on/off handling
	Event::register("24hrs", $MODULE_NAME, "roster_guild.php");
	Event::register("orgmsg", $MODULE_NAME, "notify_auto.php");
    Event::register("logOn", $MODULE_NAME, "notify_auto.php");
    Event::register("logOff", $MODULE_NAME, "notify_auto.php");
	Command::register("guild", $MODULE_NAME, "notify.php", "notify", MODERATOR, "Adding a member man. to the notify list");
	Command::register("msg", $MODULE_NAME, "notify.php", "notify", MODERATOR, "Adding a member man. to the notify list");
	Command::register("priv", $MODULE_NAME, "notify.php", "notify", MODERATOR, "Adding a member man. to the notify list");

	Settings::add("bot_notify", $MODULE_NAME, "Show/Hide Logoffs in Org Chat (Spam Prevention)", "edit", "1", "Show Logoffs;Hide Logoffs", '1;0', MODERATOR, "botnotify.txt");
	
	//Helpfile
    Help::register("notify", $MODULE_NAME, "notify.txt", MODERATOR, "Add or remove a player from the notify list.");
?>