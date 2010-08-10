<?php
	$MODULE_NAME = "ONLINE_MODULE";

	//Lastseen
	Command::register($MODULE_NAME, "lastseen.php", "lastseen", GUILDMEMBER, "Shows the logoff time of a player");

	//Online
	Command::register($MODULE_NAME, "online.php", "online", MEMBER, "Shows who is the PrivChan");
	Command::register($MODULE_NAME, "chatlist.php", "sm", MEMBER, "Shows who is the PrivChan");
	Command::register($MODULE_NAME, "chatlist.php", "chatlist", MEMBER, "Shows who is the PrivChan");
	Command::register($MODULE_NAME, "count.php", "count", MEMBER, "Shows who is the PrivChan");
	Command::register($MODULE_NAME, "count.php", "adv", MEMBER, "Shows Adventurers in PrivChan");
	Command::register($MODULE_NAME, "count.php", "agent", MEMBER, "Shows Agents in PrivChan");
	Command::register($MODULE_NAME, "count.php", "crat", MEMBER, "Shows Bureaucrats in PrivChan");
	Command::register($MODULE_NAME, "count.php", "doc", MEMBER, "Shows Doctors in PrivChan");
	Command::register($MODULE_NAME, "count.php", "enf", MEMBER, "Shows Enforcers in PrivChan");
	Command::register($MODULE_NAME, "count.php", "eng", MEMBER, "Shows Engineers in PrivChan");
	Command::register($MODULE_NAME, "count.php", "fix", MEMBER, "Shows Fixers in PrivChan");
	Command::register($MODULE_NAME, "count.php", "keep", MEMBER, "Shows Keepers in PrivChan");
	Command::register($MODULE_NAME, "count.php", "ma", MEMBER, "Shows Martial-Artists in PrivChan");
	Command::register($MODULE_NAME, "count.php", "mp", MEMBER, "Shows Meta-Physicists in PrivChan");
	Command::register($MODULE_NAME, "count.php", "nt", MEMBER, "Shows Nano-Technicians in PrivChan");
	Command::register($MODULE_NAME, "count.php", "sol", MEMBER, "Shows Soldiers in PrivChan");
	Command::register($MODULE_NAME, "count.php", "shade", MEMBER, "Shows Shades in PrivChan");
	Command::register($MODULE_NAME, "count.php", "trader", MEMBER, "Shows Traders in PrivChan");

	//Settings
	Settings::add("relaydb", $MODULE_NAME, "Database for merging online lists", "edit", "0", "text", '0', MODERATOR);
	Settings::add("online_tell", $MODULE_NAME, "Mode for Online Cmd in tells", "edit", "0", "Shows online privatechat members;Shows online guild members", "1;0", MODERATOR);
	Settings::add("count_tell", $MODULE_NAME, "Mode for Count Cmd in tells", "edit", "1", "Shows online privatechat members;Shows online guild members", "1;0", MODERATOR);
	Settings::add("chatlist_tell", $MODULE_NAME, "Mode for Chatlist Cmd in tells", "edit", "1", "Shows online privatechat members;Shows online guild members", "1;0", MODERATOR);
	Settings::add("logonline_tell", $MODULE_NAME, "Enables the Online tell on logon", "edit", "0", "On;Off", "1;0", MODERATOR);
	Settings::add("fancy_online", $MODULE_NAME, "Enables the fancy delimiters for the online display", "edit", "1", "On;Off", "1;0", MODERATOR);
	Settings::add("icon_fancy_online", $MODULE_NAME, "Enables the use of icons in fancy delimiter mode", "edit", "1", "On;Off", "1;0", MODERATOR);

	Event::register("logOn", $MODULE_NAME, "logonline.php", "none", "Sends a tell to players on logon showing who is online in org");
	Event::register("logOn", $MODULE_NAME, "logon_guild.php", "none", "Shows a logon from a member in chat and records in db");
	Event::register("logOff", $MODULE_NAME, "logoff_guild.php", "none", "Shows a logoff from a member in chat and records in db");

	//Help files
	Help::register("chatlist", $MODULE_NAME, "chatlist.txt", MEMBER, "Showing who is in the private group");
	Help::register("online", $MODULE_NAME, "online.txt", MEMBER, "Show who is on from the guild");
	Help::register("lastseen", $MODULE_NAME, "lastseen.txt", MEMBER, "Check when an orgmember was online");
?>