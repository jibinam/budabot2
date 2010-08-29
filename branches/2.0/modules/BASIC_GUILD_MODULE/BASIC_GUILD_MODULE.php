<?php
	$MODULE_NAME = "BASIC_GUILD_MODULE";

	//Setup of the Basic Guild Modules
	Event::register("setup", $MODULE_NAME, "setup.php");
    
	// Logon Handling
	Command::register($MODULE_NAME, "logon_msg.php", "logon", ALL, "Sets a Logon Msg");

    // Afk Check
	Event::register("guild", $MODULE_NAME, "afk_check.php", "Afk check");
	Command::register($MODULE_NAME, "afk.php", "afk", ALL, "Sets a member afk");
	Command::register($MODULE_NAME, "kiting.php", "kiting", ALL, "Sets a member afk kiting");

	//Verifies the Onlinelist every 1hour
	Event::register("1hour", $MODULE_NAME, "online_check.php", "Online check");

    // Alternative Characters
	Command::register($MODULE_NAME, "alts.php", "alts", ALL, "Alt Char handling");
	Command::register($MODULE_NAME, "alts.php", "altsadmin", MODERATOR, "Alt Char handling (admin)");

    // Show orgmembers
	Command::register($MODULE_NAME, "orgmembers.php", "orgmembers", ALL, "Show the Members(sorted by name) of the org");
	Command::register($MODULE_NAME, "orgranks.php", "orgranks", ALL, "Show the Members(sorted by rank) of the org");

	//Force an update of the org roster
	Command::register($MODULE_NAME, "updateorg.php", "updateorg", MODERATOR, "Forcing an update of the org roster");
	
	//Tell and Tellall
	Command::register($MODULE_NAME, "tell.php", "tell", LEADER, "Repeats an message 3 times in Orgchat");
	Command::register($MODULE_NAME, "tell.php", "tellall", LEADER, "Sends a tell to all online guildmembers");
		
	//Helpfile
    Help::register($MODULE_NAME, "afk_kiting.txt", "afk_kiting", GUILDMEMBER, "Set yourself AFK/Kiting");
    Help::register($MODULE_NAME, "alts.txt", "alts", GUILDMEMBER, "How to set alts");
	Help::register($MODULE_NAME, "altsadmin.txt", "altsadmin", GUILDMEMBER, "How to set alts (admins)");
    Help::register($MODULE_NAME, "logonmsg.txt", "LogOnMsg", GUILDMEMBER, "Changing your logon message");
    Help::register($MODULE_NAME, "orgmembers_orgranks.txt", "OrgMembers", GUILDMEMBER, "Show current OrgMembers");
    Help::register($MODULE_NAME, "tell.txt", "tell_guild", GUILDMEMBER, "Repeat a msg 3times/Send a tell to online members");
    Help::register($MODULE_NAME, "updateorg.txt", "updateorg", MODERATOR, "Force an update of orgrooster");
?>