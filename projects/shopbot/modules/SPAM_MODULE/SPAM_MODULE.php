<?php
	include_once 'spam_functions.php';

	$MODULE_NAME = "SPAM_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, 'shoppingdb');

	Event::register($MODULE_NAME, "allpackets", "shopping_messages.php", "none", "Show Shopping Messages");
	Event::register($MODULE_NAME, "msg", "tell_messages.php", "none", "Relay incoming tells into private channel");
	Event::register($MODULE_NAME, "extJoinPrivRequest", "private_group_invite.php", "none", "Handle a private group invitation");
	Event::register($MODULE_NAME, "extPriv", "private_channel_listener.php", "none", "Process commands from shopbot_master priv channel");

	Command::register($MODULE_NAME, "", "shopping_spam.php", "spamproxy", "rl", "Spams msg to clan & omni shopping channel with link to your name");
	Command::register($MODULE_NAME, "", "shopping_spam.php", "spam", "all", "Spams msg to clan & omni shopping channel with link to your name");

	Setting::add($MODULE_NAME, "shopbot_master", "Set the shopbot master", "edit", "text", "0");
	Setting::add($MODULE_NAME, "time_between_messages", "Time users must wait between spamming messages", "edit", "time", "30m", "5m;10m;15m;20m;30m;45m;60m");
?>
