<?php
	$MODULE_NAME = "RELAY_MODULE";
	
	require_once("functions.php");
	
	// Sending messages to relay
	Event::register("guild", $MODULE_NAME, "send_relay_message.php", "Send messages to relay");
	
	// Receiving messages to relay
	Command::register($MODULE_NAME, "receive_relay_message.php", "grc", ALL, "Relays incoming messages to guildchat");
	Event::register("extPriv", $MODULE_NAME, "receive_relay_message.php", "Recieving messages from relay");

	// Inivite for pgroup
	Event::register("extJoinPrivRequest", $MODULE_NAME, "invite.php", "Invitation to relay pgroup");
	
	// Logon and Logoff messages
	Event::register("logOn", $MODULE_NAME, "relay_guild_logon.php", "Sends Logon messages");
	Event::register("logOff", $MODULE_NAME, "relay_guild_logoff.php", "Sends Logoff messages");
	
	// Relay org messages between orgs
	Event::register("orgmsg", $MODULE_NAME, "org_messages.php", "Relay Org Messages");
	
	// Settings
	Settings::add("relaytype", $MODULE_NAME, "Type of relay", "edit", "1", "tell;pgroup", '1;2', MODERATOR);
	Settings::add("relaysymbol", $MODULE_NAME, "Symbol for external relay", "edit", "@", "!;#;*;@;$;+;-;Always relay", '0', MODERATOR);
	Settings::add("relaybot", $MODULE_NAME, "Bot for Guildrelay", "edit", "Off", "text", '0', MODERATOR);
?>