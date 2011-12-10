<?php
	require_once 'Topic.class.php';

	//Check macros
	Command::register($MODULE_NAME, "", "check.php", "check", "leader", "Checks who of the raidgroup is in the area");

	//Topic set/show
	Event::register($MODULE_NAME, "joinPriv", "topic.php", "none", "Show Topic when someone joins private channel");
	Command::register($MODULE_NAME, "", "topic.php", "topic", "all", "Show Topic");
	Command::register($MODULE_NAME, "", "topic.php", "edittopic", "all", "Edit topic");
	Subcommand::register($MODULE_NAME, "", "topic.php", "topic (.+)", "leader", "topic", "Change topic");
	Setting::add($MODULE_NAME, "topic", "Topic for Priv Channel", "edit", "text", "");
	Setting::add($MODULE_NAME, "rally", "Rally for topic", "edit", "text", "");
	Setting::add($MODULE_NAME, "topic_setby", "Name of character who set the topic", "noedit", "text", "");
	Setting::add($MODULE_NAME, "topic_time", "Time that topic was set", "noedit", "number", "0");

	//Leader
	Command::register($MODULE_NAME, "priv", "leader.php", "leader", "all", "Sets the Leader of the raid");
	Subcommand::register($MODULE_NAME, "priv", "leader.php", "leader (.+)", "leader", "leader", "Set a new leader");
	Command::register($MODULE_NAME, "", "leaderecho_cmd.php", "leaderecho", "leader", "Set if the text of the leader will be repeated");
	Event::register($MODULE_NAME, "priv", "leaderecho.php", "none", "Repeats what leader says in private channel");
	Event::register($MODULE_NAME, "leavePriv", "leader.php", "none", "Removes leader when the leader leaves the channel");
	Setting::add($MODULE_NAME, "leaderecho", "Repeat the text of the raidleader", "edit", "options", "1", "false;true", '0;1');
	Setting::add($MODULE_NAME, "leaderecho_color", "Color for Raidleader echo", "edit", "color", "<font color=#FFFF00>");

	//Assist
	Command::register($MODULE_NAME, "", "assist.php", "assist", "all", "Show assist macro");
	Command::register($MODULE_NAME, "", "leaderecho_cmd.php", "leaderecho", "leader", "Set if the text of the leader will be repeated");
	Subcommand::register($MODULE_NAME, "", "assist.php", "assist (.+)", "leader", "assist", "Set a new assist");
	Command::register($MODULE_NAME, "", "heal_assist.php", "heal", "all", "Creates/showes an Doc Assist macro");
	Subcommand::register($MODULE_NAME, "", "heal_assist.php", "heal (.+)", "leader", "heal", "Set a new heal assist");

	//Tell
	Command::register($MODULE_NAME, "", "tell.php", "tell", "leader", "Repeats a message 3 times");
	Command::register($MODULE_NAME, "", "cmd.php", "cmd", "leader", "Creates a highly visible messaage");

	Command::register($MODULE_NAME, "", "rally.php", "rally", "all", "Shows or sets the rally waypoint");

	//Helpfiles
	Help::register($MODULE_NAME, "assist", "assist.txt", "all", "Creating an Assist Macro");
	Help::register($MODULE_NAME, "check", "check.txt", "all", "See of the ppls are in the area");
	Help::register($MODULE_NAME, "heal", "healassist.txt", "all", "Creating an Healassist Macro");
	Help::register($MODULE_NAME, "leader", "leader.txt", "all", "Set a Leader of a Raid/Echo on/off");
	Help::register($MODULE_NAME, "tell", "tell.txt", "leader", "How to use tell");
	Help::register($MODULE_NAME, "topic", "topic.txt", "raidleader", "Set the Topic of the raid");
	Help::register($MODULE_NAME, "cmd", "cmd.txt", "leader", "How to use cmd");
?>
