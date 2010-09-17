<?php 
	$MODULE_NAME = "BASIC_CHAT_MODULE";

	//Invite/Leave/lock commands
	Settings::add("topic_guild_join", $MODULE_NAME, "Show Topic in guild on join", "edit", "0", "ON;OFF", "1;0", MODERATOR, "topic_show_guild.txt");
	Settings::add("priv_status", "no", $MODULE_NAME, "hide", "open");
	Settings::add("priv_status_reason", $MODULE_NAME, "no", "hide", "not set");	

	//Check macros
	Command::register($MODULE_NAME, "check.php", "check", LEADER, "Checks who of the raidgroup is in the area");
	
	//Topic set/show
	Event::register("joinPriv", $MODULE_NAME, "topic.php", "Show Topic when someone joins PrivChat");
	Event::register("logOn", $MODULE_NAME, "topic_logon.php", "Show Topic on logon of members");
	Command::register($MODULE_NAME, "topic.php", "topic", ALL, "Show Topic");
	Command::register($MODULE_NAME, "topic.php", "settopic", LEADER, "Change Topic");
	Command::register($MODULE_NAME, "topic.php", "cleartopic", LEADER, "Clear Topic");
	Settings::add("topic", $MODULE_NAME, "Topic for Priv Channel", "noedit", "No Topic set.");	
	Settings::add("topic_setby", $MODULE_NAME, "no", "hide", "none");
	Settings::add("topic_time", $MODULE_NAME, "no", "hide", time());

    //Afk Check
	Event::register("priv", $MODULE_NAME, "afk_check.php", "Afk check");
	Command::register($MODULE_NAME, "afk.php", "afk", ALL, "Sets a member afk");

	//Leader
	Command::register($MODULE_NAME, "leader.php", "leader", ALL, "Sets the Leader of the raid");
	Command::register($MODULE_NAME, "leader.php", "setleader", LEADER, "Set a specific Leader");
	Command::register($MODULE_NAME, "leaderecho_cmd.php", "leaderecho", LEADER, "Set if the text of the leader will be repeated");
	Event::register("priv", $MODULE_NAME, "leaderecho.php", "leader echo");
	Settings::add("leaderecho", $MODULE_NAME, "Repeat the text of the raidleader", "edit", "1", "ON;OFF", "1;0");
	Settings::add("leaderecho_color", $MODULE_NAME, "Color for Raidleader echo", "edit", "<font color=#FFFF00>", "color");

	//Assist
	Command::register($MODULE_NAME, "assist.php", "assist", ALL, "Creates/shows an Assist macro");
	Command::register($MODULE_NAME, "assist.php", "setassist", LEADER, "Set a new assist");
	Command::register($MODULE_NAME, "heal_assist.php", "heal", ALL, "Creates/showes an Doc Assist macro");
	Command::register($MODULE_NAME, "heal_assist.php", "setheal", LEADER, "Set a new Doc assist");
	Command::register($MODULE_NAME, "heal_assist.php", "healassist", ALL, "Creates/showes an Doc Assist macro");
	Command::register($MODULE_NAME, "heal_assist.php", "sethealassist", LEADER, "Set a new Doc assist");

	//Tell
	Command::register($MODULE_NAME, "tell.php", "tell", ALL, "Repeats a message 3 times");
	Command::register($MODULE_NAME, "cmd.php", "cmd", LEADER, "Creates a highly visible messaage");
	
	//updateme
	Command::register($MODULE_NAME, "updateme.php", "updateme", ALL, "Updates Charinfos from a player");
	
	//Help files
	Help::register($MODULE_NAME, "afk.txt", "afk_priv", ALL, "Going AFK");
	Help::register($MODULE_NAME, "assist.txt", "assist", ALL, "Creating an Assist Macro");
	Help::register($MODULE_NAME, "check.txt", "check", ALL, "See of the ppls are in the area");
	Help::register($MODULE_NAME, "healassist.txt", "heal_assist", ALL, "Creating an Healassist Macro");
	Help::register($MODULE_NAME, "leader.txt", "leader", ALL, "Set a Leader of a Raid/Echo on/off");
	Help::register($MODULE_NAME, "priv_news.txt", "priv_news", RAIDLEADER, "Set Privategroup News");
	Help::register($MODULE_NAME, "tell.txt", "tell", LEADER, "How to use tell");
	Help::register($MODULE_NAME, "topic.txt", "topic", RAIDLEADER, "Set the Topic of the raid");
	Help::register($MODULE_NAME, "updateme.txt", "updateme", ALL, "Update your character infos");
	Help::register($MODULE_NAME, "cmd.txt", "cmd", ALL, "How to use cmd");
?>
