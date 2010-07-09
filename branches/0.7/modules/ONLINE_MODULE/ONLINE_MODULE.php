<?php
	$MODULE_NAME = "ONLINE_MODULE";

	//Lastseen
	$this->command("", $MODULE_NAME, "lastseen.php", "lastseen", GUILDMEMBER, "Shows the logoff time of a player");

	//Online
	$this->command("", $MODULE_NAME, "online.php", "online", MEMBER, "Shows who is the PrivChan");
	$this->command("", $MODULE_NAME, "chatlist.php", "sm", MEMBER, "Shows who is the PrivChan");
	$this->command("", $MODULE_NAME, "chatlist.php", "chatlist", MEMBER, "Shows who is the PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "count", MEMBER, "Shows who is the PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "adv", MEMBER, "Shows Adventurers in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "agent", MEMBER, "Shows Agents in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "crat", MEMBER, "Shows Bureaucrats in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "doc", MEMBER, "Shows Doctors in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "enf", MEMBER, "Shows Enforcers in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "eng", MEMBER, "Shows Engineers in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "fix", MEMBER, "Shows Fixers in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "keep", MEMBER, "Shows Keepers in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "ma", MEMBER, "Shows Martial-Artists in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "mp", MEMBER, "Shows Meta-Physicists in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "nt", MEMBER, "Shows Nano-Technicians in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "sol", MEMBER, "Shows Soldiers in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "shade", MEMBER, "Shows Shades in PrivChan");
	$this->command("", $MODULE_NAME, "count.php", "trader", MEMBER, "Shows Traders in PrivChan");

	//Group
	$this->regGroup("online", $MODULE_NAME, "Show who is online(guild or privatechat)", "adv", "agent", "crat", "doc", "enf", "eng", "fix", "keep", "ma", "mp", "nt", "sol", "shade", "trader", "sm", "chatlist", "online", "count");

	//Settings
	$this->addsetting("relaydb", $MODULE_NAME, "Database for merging online lists", "edit", "0", "text", '0', MODERATOR);
	$this->addsetting("online_tell", $MODULE_NAME, "Mode for Online Cmd in tells", "edit", "0", "Shows online privatechat members;Shows online guild members", "1;0", MODERATOR);
	$this->addsetting("count_tell", $MODULE_NAME, "Mode for Count Cmd in tells", "edit", "1", "Shows online privatechat members;Shows online guild members", "1;0", MODERATOR);
	$this->addsetting("chatlist_tell", $MODULE_NAME, "Mode for Chatlist Cmd in tells", "edit", "1", "Shows online privatechat members;Shows online guild members", "1;0", MODERATOR);
	$this->addsetting("logonline_tell", $MODULE_NAME, "Enables the Online tell on logon", "edit", "0", "On;Off", "1;0", MODERATOR);
	$this->addsetting("fancy_online", $MODULE_NAME, "Enables the fancy delimiters for the online display", "edit", "1", "On;Off", "1;0", MODERATOR);
	$this->addsetting("icon_fancy_online", $MODULE_NAME, "Enables the use of icons in fancy delimiter mode", "edit", "1", "On;Off", "1;0", MODERATOR);

	$this->event("logOn", $MODULE_NAME, "logonline.php", "none", "Sends a tell to players on logon showing who is online in org");
	$this->event("logOn", $MODULE_NAME, "logon_guild.php", "none", "Shows a logon from a member");
	$this->event("logOff", $MODULE_NAME, "logoff_guild.php", "none", "Shows a logoff from a member");

	//Help files
	$this->help("chatlist", $MODULE_NAME, "chatlist.txt", MEMBER, "Showing who is in the private group");
	$this->help("online", $MODULE_NAME, "online.txt", MEMBER, "Show who is on from the guild");
	$this->help("lastseen", $MODULE_NAME, "lastseen.txt", MEMBER, "Check when an orgmember was online");
?>