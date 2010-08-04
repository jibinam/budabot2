<?php 
	$MODULE_NAME = "BASIC_CHAT_MODULE";

	//Invite/Leave/lock commands
	Settings::add("topic_guild_join", $MODULE_NAME, "Show Topic in guild on join", "edit", "0", "ON;OFF", "1;0", MODERATOR, $MODULE_NAME, "topic_show_guild.txt");
	Settings::add("priv_status", "no", $MODULE_NAME, "hide", "open");
	Settings::add("priv_status_reason", $MODULE_NAME, "no", "hide", "not set");	

	//Check macros
	Command::register("priv", $MODULE_NAME, "check.php", "check", RAIDLEADER, "Checks who of the raidgroup is in the area");	
	
	//Topic set/show
	Event::register("joinPriv", $MODULE_NAME, "topic.php", "topic", "Show Topic when someone joins PrivChat");
	Event::register("logOn", $MODULE_NAME, "topic_logon.php", "none", "Show Topic on logon of members");
	Command::register("", $MODULE_NAME, "topic.php", "topic", ALL, "Show Topic");
	Subcommand::register("", $MODULE_NAME, "topic.php", "topic (.+)", LEADER, "topic", "Change Topic");
	Settings::add("topic", $MODULE_NAME, "Topic for Priv Channel", "noedit", "No Topic set.");	
	Settings::add("topic_setby", $MODULE_NAME, "no", "hide", "none");
	Settings::add("topic_time", $MODULE_NAME, "no", "hide", time());

    //Afk Check
	Event::register("priv", $MODULE_NAME, "afk_check.php", "afk");
	Command::register("priv", $MODULE_NAME, "afk.php", "afk", ALL, "Sets a member afk");

	//Leader
	Command::register("priv", $MODULE_NAME, "leader.php", "leader", ALL, "Sets the Leader of the raid");
	Subcommand::register("priv", $MODULE_NAME, "leader.php", "leader (.+)", LEADER, "leader", "Set a specific Leader");
	Command::register("priv", $MODULE_NAME, "leaderecho_cmd.php", "leaderecho", LEADER, "Set if the text of the leader will be repeated");
	Event::register("priv", $MODULE_NAME, "leaderecho.php", "leader");
	Settings::add("leaderecho", $MODULE_NAME, "Repeat the text of the raidleader", "edit", "1", "ON;OFF", "1;0");
	Settings::add("leaderecho_color", $MODULE_NAME, "Color for Raidleader echo", "edit", "<font color=#FFFF00>", "color");

	//Assist
	Command::register("", $MODULE_NAME, "assist.php", "assist", ALL, "Creates/shows an Assist macro");
	Subcommand::register("", $MODULE_NAME, "assist.php", "assist (.+)", LEADER, "assist", "Set a new assist");
	Command::register("", $MODULE_NAME, "heal_assist.php", "heal", ALL, "Creates/showes an Doc Assist macro");
	Subcommand::register("", $MODULE_NAME, "heal_assist.php", "heal (.+)", LEADER, "heal", "Set a new Doc assist");
	Command::register("", $MODULE_NAME, "heal_assist.php", "healassist", ALL, "Creates/showes an Doc Assist macro");
	Subcommand::register("", $MODULE_NAME, "heal_assist.php", "healassist (.+)", LEADER, "heal", "Set a new Doc assist");

	//Tell
	Command::register("priv", $MODULE_NAME, "tell.php", "tell", ALL, "Repeats a Message 3times");
	
	//updateme
	Command::register("", $MODULE_NAME, "updateme.php", "updateme", ALL, "Updates Charinfos from a player");

	//Set admin and user news
	Command::register("", $MODULE_NAME, "set_news.php", "privnews", RAIDLEADER, "Set news that are shown on privjoin");
	Command::register("", $MODULE_NAME, "set_news.php", "adminnews", MODERATOR, "Set adminnews that are shown on privjoin");
	Settings::add("news", $MODULE_NAME, "no", "hide", "Not set.");
	Settings::add("adminnews", $MODULE_NAME, "no", "hide", "Not set.");	
	
	//Help files
	Help::register("afk_priv", $MODULE_NAME, "afk.txt", ALL, "Going AFK");
	Help::register("assist", $MODULE_NAME, "assist.txt", ALL, "Creating an Assist Macro");
	Help::register("check", $MODULE_NAME, "check.txt", ALL, "See of the ppls are in the area");
	Help::register("heal_assist", $MODULE_NAME, "healassist.txt", ALL, "Creating an Healassist Macro");
	Help::register("leader", $MODULE_NAME, "leader.txt", ALL, "Set a Leader of a Raid/Echo on/off");
	Help::register("priv_news", $MODULE_NAME, "priv_news.txt", RAIDLEADER, "Set Privategroup News");
	Help::register("tell", $MODULE_NAME, "tell.txt", LEADER, "Repeating of a msg 3times");
	Help::register("topic", $MODULE_NAME, "topic.txt", RAIDLEADER, "Set the Topic of the raid");
	Help::register("updateme", $MODULE_NAME, "updateme.txt", ALL, "Update your character infos");
?>
