<?php

   /*
   ** Author: Legendadv (RK2)
   ** IRC RELAY MODULE
   ** Version = 0.2
   ** Developed for: Budabot(http://aodevs.com/index.php/topic,512.0.html)
   **
   */

	$MODULE_NAME = "IRC_MODULE";
	if (Settings::get('irc_channel') == "") {
		if($chatBot->guild == "") {
			$channel = "#".strtolower($chatBot->name);
		} else {
			if(strpos($chatBot->guild, " ")) {
				$sandbox = explode(" ", $chatBot->guild);
				for ($i = 0; $i < count($sandbox); $i++) {
					$channel .= ucfirst(strtolower($sandbox[$i]));
				}
				$channel = "#".$channel;
			}
			else {
				$channel = "#".$chatBot->guild;
			}
		}
	}

	//Auto start IRC connection, or turn it off
	Event::register("connect", $MODULE_NAME, "set_irc_link.php", "Sets IRC status at bootup.");
	
	//Commands
	Command::register($MODULE_NAME, "irc_connect.php", "startirc", ADMIN, "Connect to IRC");
	Command::register($MODULE_NAME, "online_irc.php", "onlineirc", ALL, "View who is in IRC chat");
	
	//Command settings
	Command::register($MODULE_NAME, "set_irc_settings.php", "setirc", ADMIN, "Manually set IRC settings");
	
	//IRC Relay
  	Event::register("2sec", $MODULE_NAME, "irc_check.php", "Receive messages from IRC");
	
	//In-game relay
	Event::register("priv", $MODULE_NAME, "relay_irc_out.php", "Relay (priv) messages to IRC");
	Event::register("guild", $MODULE_NAME, "relay_irc_out.php", "Relay (guild) messages to IRC");
	
	//Notifications
	Event::register("joinPriv", $MODULE_NAME, "irc_relay_joined.php", "Sends joined channel messages");
	Event::register("leavePriv", $MODULE_NAME, "irc_relay_left.php", "Sends left channel messages");
	Event::register("logOn", $MODULE_NAME, "irc_relay_joined.php", "Shows a logon from a member");
	Event::register("logOff", $MODULE_NAME, "irc_relay_left.php", "Shows a logoff from a member");
	
	//Settings
	Settings::add("irc_status", $MODULE_NAME, "Status of IRC uplink", "noedit", "0", "Offline;Online", "0;1", MODERATOR, "irc_help.txt");
	Settings::add("irc_server", $MODULE_NAME, "IRC server to connect to", "noedit", "irc.funcom.com", "none", "0", MODERATOR, "irc_help.txt");
	Settings::add("irc_port", $MODULE_NAME, "IRC server port to use", "noedit", "6667", "none", "0", MODERATOR, "irc_help.txt");
	Settings::add("irc_nickname", $MODULE_NAME, "Nickname to use while in IRC", "noedit", $chatBot->name, "none", "0", MODERATOR, "irc_help.txt");
	Settings::add("irc_channel", $MODULE_NAME, "Channel to join", "noedit", "$channel", "none", "0", MODERATOR, "irc_help.txt");
	Settings::add("irc_autoconnect", $MODULE_NAME, "Connect to IRC at bootup", "edit", "0", "No;Yes", "0;1", MODERATOR, "irc_help.txt");
	Settings::add("irc_debug_ping", $MODULE_NAME, "IRC Debug Option: Show pings in console", "edit", "0", "Off;On", "0;1", MODERATOR, "irc_help.txt");
	Settings::add("irc_debug_messages", $MODULE_NAME, "IRC Debug Option: Show events in console", "edit", "0", "Off;On", "0;1", MODERATOR, "irc_help.txt");
	Settings::add("irc_debug_all", $MODULE_NAME, "IRC Debug Option: Log everything", "edit", "0", "Off;On", "0;1", MODERATOR, "irc_help.txt");
	
	//Help files
	Help::register($MODULE_NAME, "irc_help.txt", "irc", ALL, "How to use the IRC plugin");
?>