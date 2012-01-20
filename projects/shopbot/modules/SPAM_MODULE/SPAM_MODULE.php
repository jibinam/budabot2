<?php
	include_once 'spam_functions.php';
	
	DB::loadSQLFile($MODULE_NAME, 'shoppingdb');

	Event::register($MODULE_NAME, "allpackets", "shopping_messages.php", "Show Shopping Messages");
	Event::register($MODULE_NAME, "msg", "tell_messages.php", "Relay incoming tells into private channel");
	Event::register($MODULE_NAME, "extJoinPrivRequest", "private_group_invite.php", "Handle a private group invitation");
	Event::register($MODULE_NAME, "extPriv", "private_channel_listener.php", "Process commands from shopbot_master priv channel");

	Command::register($MODULE_NAME, "", "shopping_spam.php", "spamproxy", "rl", "Spams msg to clan & omni shopping channel with link to your name");
	Command::register($MODULE_NAME, "", "shopping_spam.php", "spam", "all", "Spams msg to clan & omni shopping channel with link to your name");

	Setting::add($MODULE_NAME, "shopbot_master", "Set the shopbot master", "edit", "text", "0");
	Setting::add($MODULE_NAME, "time_between_messages", "Time users must wait between spamming messages", "edit", "time", "30m", "5m;10m;15m;20m;30m;45m;60m");
	Setting::add($MODULE_NAME, "add_ql_info", "Enable showing ql as part of item links", "edit", "options", "0", "true;false", "1;0");
?>
