<?php
	require_once 'functions.php';

	$MODULE_NAME = "SPAMBOT_MODULE"; 

	bot::event($MODULE_NAME, "setup", "setup.php");

	bot::command("msg", "$MODULE_NAME/subscriptions.php", "register", "all", "Spambot Register");
	bot::command("msg", "$MODULE_NAME/subscriptions.php", "unregister", "all", "Spambot Unregister");

	bot::command("msg", "$MODULE_NAME/subscriptions.php", "subscriptions", "all", "Subscriptions");
	bot::command("msg", "$MODULE_NAME/subscriptions.php", "status", "all", "Subscription Status");
	bot::command("msg", "$MODULE_NAME/subscriptions.php", "subscribe", "all", "Subscribe");
	bot::command("msg", "$MODULE_NAME/subscriptions.php", "unsubscribe", "all", "Unsubscribe");
	bot::command("msg", "$MODULE_NAME/subscriptions.php", "subscribegroup", "all", "Subscribe Group");
	bot::command("msg", "$MODULE_NAME/subscriptions.php", "unsubscribegroup", "all", "Unsubscribe Group");

	bot::command("msg", "$MODULE_NAME/spam.php", "spam", "all", "Spam");
	bot::command("msg", "$MODULE_NAME/spamhistory.php", "history", "all", "History");
	bot::command("msg", "$MODULE_NAME/catch_tell.php", "spamrelay", "all", "Catch tell from master relay bot");

	bot::command("msg", "$MODULE_NAME/orgbots.php", "addbot", "rl", "Addbot Subscriptions");
	bot::command("msg", "$MODULE_NAME/orgbots.php", "rembot", "rl", "Rembot Subscriptions");
	bot::command("msg", "$MODULE_NAME/orgbots.php", "listbots", "rl", "Listbot Subscriptions");

	bot::command("msg", "$MODULE_NAME/subscriptions.php", "regtwink", "rl", "Register Twink Main");
	bot::command("msg", "$MODULE_NAME/subscriptions.php", "forceremove", "rl", "Force Remove Registered Player");

	bot::command("msg", "$MODULE_NAME/statistics.php", "statistics", "rl", "Registration Stats");
	bot::command("msg", "$MODULE_NAME/dblookup.php", "dblookup", "rl", "Database Lookup");
	bot::command("msg", "$MODULE_NAME/lastspam.php", "lastspam", "rl", "Lastspam Lookup");
	bot::command("msg", "$MODULE_NAME/dbconvert.php", "dbconvert", "admin", "Database Conversion");

	bot::help($MODULE_NAME, "spam", "spam.txt", "all", "Spam", "Spam");
	bot::help($MODULE_NAME, "history", "spamhistory.txt", "all", "History", "Spam History");
	bot::help($MODULE_NAME, "orgbots", "orgbots.txt", "all", "Org Bots", "Org Bots In Relay");
	bot::help($MODULE_NAME, "botadmin", "orgbotsadm.txt", "rl", "Bot Admin", "Org Bots In Relay Admin");
	bot::help($MODULE_NAME, "administration", "modhelp.txt", "rl", "Guide for Moderators", "Administration");

	bot::addsetting($MODULE_NAME, "tarabotname",   "Tarasque Bot", "edit", "Tarabot", "text");
	bot::addsetting($MODULE_NAME, "pvpnetname",    "Pvpnet", "edit", "Pvpnet", "text");
	bot::addsetting($MODULE_NAME, "twinknetname", "Twinknet", "edit", "Twinknet", "text");
	bot::addsetting($MODULE_NAME, "masterrelay", "Master Relay", "edit", "Linknet", "text");
	bot::addsetting($MODULE_NAME, "slavename1", "Slave Relay 1", "edit", "Linknet1", "text");
	bot::addsetting($MODULE_NAME, "slavename2", "Slave Relay 2", "edit", "Linknet2", "text");
	bot::addsetting($MODULE_NAME, "slavename3", "Slave Relay 3", "edit", "Linknet3", "text");
	bot::addsetting($MODULE_NAME, "slavename4", "Slave Relay 4", "edit", "Linknet4", "text");
	bot::addsetting($MODULE_NAME, "slavename5", "Slave Relay 5", "edit", "Linknet5", "text");
	bot::addsetting($MODULE_NAME, "slavename6", "Slave Relay 6", "edit", "Linknet6", "text");

	bot::addsetting($MODULE_NAME, "general", "General Channel", "edit", "0", "ON;OFF", "1;0");
	bot::addsetting($MODULE_NAME, "shopping", "Shopping Channel", "edit", "0", "ON;OFF", "1;0");
	bot::addsetting($MODULE_NAME, "raids", "Raids Channel", "edit", "0", "ON;OFF", "1;0");

?>