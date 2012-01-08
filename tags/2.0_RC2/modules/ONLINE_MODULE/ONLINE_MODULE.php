<?php
	require_once "online_func.php";

	$MODULE_NAME = "ONLINE_MODULE";

	//Setup of the Online Modules
	bot::event($MODULE_NAME, "setup", "setup.php");
	
	bot::loadSQLFile($MODULE_NAME, "guild_chatlist");

	//Lastseen
	bot::command("", "$MODULE_NAME/lastseen.php", "lastseen", "guild", "Shows the logoff time of a player");

	//Online
	bot::command("", "$MODULE_NAME/online.php", "online", "all", "Shows who is the PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "count", "all", "Shows who is the PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "adv", "all", "Shows Adventurers in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "agent", "all", "Shows Agents in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "crat", "all", "Shows Bureaucrats in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "doc", "all", "Shows Doctors in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "enf", "all", "Shows Enforcers in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "eng", "all", "Shows Engineers in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "fix", "all", "Shows Fixers in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "keep", "all", "Shows Keepers in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "ma", "all", "Shows Martial-Artists in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "mp", "all", "Shows Meta-Physicists in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "nt", "all", "Shows Nano-Technicians in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "sol", "all", "Shows Soldiers in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "shade", "all", "Shows Shades in PrivChan");
	bot::command("", "$MODULE_NAME/count.php", "trader", "all", "Shows Traders in PrivChan");

	//Group
	bot::regGroup("online", $MODULE_NAME, "Show who is online(guild or privatechat)", "adv", "agent", "crat", "doc", "enf", "eng", "fix", "keep", "ma", "mp", "nt", "sol", "shade", "trader", "online", "count");

	//Settings
	bot::addsetting($MODULE_NAME, "relaydb", "Database for merging online lists", "edit", "0", "text", '0', "mod");
	bot::addsetting($MODULE_NAME, "chatlist_tell", "Mode for Chatlist Cmd in tells", "edit", "1", "Shows online privatechat members;Shows online guild members", "1;0");
	bot::addsetting($MODULE_NAME, "fancy_online", "Enables the fancy delimiters for the online display", "edit", "1", "On;Off", "1;0");
	bot::addsetting($MODULE_NAME, "icon_fancy_online", "Enables the use of icons in fancy delimiter mode", "edit", "1", "On;Off", "1;0");

	bot::event($MODULE_NAME, "logOn", "logonline.php", "none", "Sends a tell to players on logon showing who is online in org");
	
	bot::event($MODULE_NAME, "logOn", "notify_logon_guild.php", "none", "Shows an org member login in chat");
	bot::event($MODULE_NAME, "logOff", "notify_logoff_guild.php", "none", "Shows an org member logoff in chat");
	
	bot::event($MODULE_NAME, "logOn", "record_logon_guild.php", "none", "Records an org member login in db");
	bot::event($MODULE_NAME, "logOff", "record_logoff_guild.php", "none", "Records an org member logoff in db");

	//Help files
	bot::help($MODULE_NAME, "online", "online.txt", "guild", "Show who is on from the guild");
	bot::help($MODULE_NAME, "lastseen", "lastseen.txt", "guild", "Check when an orgmember was online");
?>