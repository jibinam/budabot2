<?php
	require_once 'Rally.class.php';
	
	$chatBot->registerInstance($MODULE_NAME, 'Rally', new Rally);

	// Check macros
	$command->register($MODULE_NAME, "", "check.php", "check", "all", "Checks who of the raidgroup is in the area");

	// Topic set/show
	$event->register($MODULE_NAME, "joinPriv", "topic_logon.php", "Show topic when someone joins the private channel");
	$event->register($MODULE_NAME, "logOn", "topic_logon.php", "Show Topic on logon of members");
	$command->register($MODULE_NAME, "", "topic.php", "topic", "all", "Show Topic");
	$subcommand->register($MODULE_NAME, "", "topic_change.php", "topic (.+)", "rl", "topic", "Change Topic");
	$setting->add($MODULE_NAME, "topic", "Topic for Priv Channel", "noedit", "text", '');
	$setting->add($MODULE_NAME, "topic_setby", "Character who set the topic", "noedit", "text", '');
	$setting->add($MODULE_NAME, "topic_time", "Time the topic was set", "noedit", "text", '');

	// Leader/Leader echo
	$command->register($MODULE_NAME, "priv", "leader.php", "leader", "all", "Sets the Leader of the raid");
	$subcommand->register($MODULE_NAME, "priv", "leader_set.php", "leader (.+)", "rl", "leader", "Set a specific Leader");
	$command->register($MODULE_NAME, "", "leaderecho_cmd.php", "leaderecho", "rl", "Set if the text of the leader will be repeated", 'leader');
	$event->register($MODULE_NAME, "priv", "leaderecho.php", "Repeats what the leader says in the color of leaderecho_color setting");
	$event->register($MODULE_NAME, "leavePriv", "leader_leave.php", "Removes leader when the leader leaves the channel", 'leader');
	$setting->add($MODULE_NAME, "leaderecho", "Repeat the text of the leader", "edit", "options", "1", "true;false", "1;0");
	$setting->add($MODULE_NAME, "leaderecho_color", "Color for leader echo", "edit", "color", "<font color=#FFFF00>");

	// Assist
	$command->register($MODULE_NAME, "", "assist.php", "assist", "all", "Shows an Assist macro");
	$commandAlias->register($MODULE_NAME, "assist", "callers");
	$subcommand->register($MODULE_NAME, "", "assist_set.php", "assist (.+)", "rl", "assist", "Set a new assist");
	
	// Heal Assist
	$command->register($MODULE_NAME, "", "healassist.php", "heal", "all", "Creates/showes an Doc Assist macro");
	$subcommand->register($MODULE_NAME, "", "healassist_set.php", "heal (.+)", "rl", "heal", "Set a new Doc assist");
	$commandAlias->register($MODULE_NAME, "heal", "healassist");

	// Tell
	$command->register($MODULE_NAME, "", "tell.php", "tell", "rl", "Repeats a message 3 times");
	$command->register($MODULE_NAME, "", "cmd.php", "cmd", "rl", "Creates a highly visible messaage");

	// Helpfiles
	$help->register($MODULE_NAME, "assist", "assist.txt", "all", "Creating an Assist Macro");
	$help->register($MODULE_NAME, "check", "check.txt", "all", "See of the ppls are in the area");
	$help->register($MODULE_NAME, "heal", "healassist.txt", "all", "Creating an Healassist Macro");
	$help->register($MODULE_NAME, "leader", "leader.txt", "all", "Set a Leader of a Raid/Echo on/off");
	$help->register($MODULE_NAME, "tell", "tell.txt", "rl", "How to use tell");
	$help->register($MODULE_NAME, "topic", "topic.txt", "all", "Set the Topic of the raid");
	$help->register($MODULE_NAME, "cmd", "cmd.txt", "rl", "How to use cmd");
	$help->register($MODULE_NAME, "rally", "rally.txt", "rl", "How to set or view the rally");
?>
