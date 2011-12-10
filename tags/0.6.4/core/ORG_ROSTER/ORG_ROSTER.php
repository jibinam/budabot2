<?php
$MODULE_NAME = "ORG_ROSTER";
$PLUGIN_VERSION = 0.1;

	//Setup of the Basic Guild Modules
	bot::regevent("setup", "$MODULE_NAME/setup.php");

    // Org Roster list creation and Notify on/off handling
	bot::regevent("24hrs", "$MODULE_NAME/roster_guild.php");
	bot::regevent("orgmsg", "$MODULE_NAME/notify_auto.php");
    bot::regevent("logOn", "$MODULE_NAME/notify_auto.php");
    bot::regevent("logOff", "$MODULE_NAME/notify_auto.php");
	bot::command("guild", "$MODULE_NAME/notify.php", "notify", "mod", "Adding a member man. to the notify list");
	bot::command("msg", "$MODULE_NAME/notify.php", "notify", "mod", "Adding a member man. to the notify list");
	bot::command("priv", "$MODULE_NAME/notify.php", "notify", "mod", "Adding a member man. to the notify list");
	bot::command("msg", "$MODULE_NAME/notify.php", "botnotify", "mod", "Turn off/on orgchat org-member logged off messages");

	//Helpfile
    bot::help("notify", "$MODULE_NAME/notify.txt", "mod", "Add or remove a player from the notify list.", "Notify List");
	bot::help("botnotify", "$MODULE_NAME/botnotify.txt", "mod", "Turning on/off org-member logged off messages", "Bot-Silence");
?>