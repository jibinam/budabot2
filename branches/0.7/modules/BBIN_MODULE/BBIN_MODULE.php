<?php

   /*
   ** Author: Mindrila (RK1)
   ** Credits: Legendadv (RK2)
   ** BUDABOT IRC NETWORK MODULE
   ** Version = 0.1
   ** Developed for: Budabot(http://budabot.com)
   **
   */

	$MODULE_NAME = "BBIN_MODULE";
	if($this->settings['bbin_channel'] == "") {
		if($this->vars['my guild'] == "") {
			$channel = "#".strtolower($this->vars['name']);
		}
		else {
			if(strpos($this->vars['my guild']," ")) {
			$sandbox = explode(" ",$this->vars['my guild']);
				for ($i = 0; $i < count($sandbox); $i++) {
					$channel .= ucfirst(strtolower($sandbox[$i]));
				}
				$channel = "#".$channel;
			}
			else {
				$channel = "#".$this->vars['my guild'];
			}
		}
	}
	//Setup
	$this->loadSQLFile($MODULE_NAME, "bbin_chatlist");
	
	//Auto start BBIN connection, or turn it off
	$this->event("connect", $MODULE_NAME, "set_bbin_link.php", "none", "Sets BBIN status at bootup.");
	
	//Commands
	$this->command("msg", $MODULE_NAME, "bbin_connect.php", "startbbin", ADMIN, "Connect to BBIN");
	
	//Command settings
	$this->command("msg", $MODULE_NAME, "set_bbin_settings.php", "setbbin", ADMIN, "Manually set BBIN settings");
	
	//BBIN Relay
	$this->event("2sec", $MODULE_NAME, "bbin_loop.php", "none", "The main BBIN message loop");
	
	//In-game relay
	$this->event("priv", $MODULE_NAME, "relay_bbin_out.php", "none", "Relay (priv) messages to BBIN");
	$this->event("guild", $MODULE_NAME, "relay_bbin_out.php", "none", "Relay (guild) messages to BBIN");
	
	//Notifications
	$this->event("joinPriv", $MODULE_NAME, "bbin_relay_joined.php", "none", "Sends joined channel messages");
	$this->event("leavePriv", $MODULE_NAME, "bbin_relay_left.php", "none", "Sends left channel messages");
	$this->event("logOn", $MODULE_NAME, "bbin_relay_joined.php", "none", "Shows a logon from a member");
	$this->event("logOff", $MODULE_NAME, "bbin_relay_left.php", "none", "Shows a logoff from a member");
	
	//Settings
	Settings::add("bbin_status", $MODULE_NAME, "Status of BBIN uplink", "noedit", "0", "Offline;Online", "0;1", MODERATOR, "bbin_help.txt");
	Settings::add("bbin_server", $MODULE_NAME, "IRC server to connect to", "noedit", "irc.funcom.com", "none", "0", MODERATOR, "bbin_help.txt");
	Settings::add("bbin_port", $MODULE_NAME, "IRC server port to use", "noedit", "6667", "none", "0", MODERATOR, "bbin_help.txt");
	Settings::add("bbin_nickname", $MODULE_NAME, "Nickname to use while in IRC", "noedit", "{$this->vars['name']}", "none", "0", MODERATOR, "bbin_help.txt");
	Settings::add("bbin_channel", $MODULE_NAME, "Channel to join", "noedit", "$channel", "none", "0", MODERATOR, "bbin_help.txt");
	Settings::add("bbin_autoconnect", $MODULE_NAME, "Connect to IRC at bootup", "edit", "0", "No;Yes", "0;1", MODERATOR, "bbin_help.txt");
	Settings::add("bbin_debug_ping", $MODULE_NAME, "IRC Debug Option: Show pings in console", "edit", "0", "Off;On", "0;1", MODERATOR, "bbin_help.txt");
	Settings::add("bbin_debug_messages", $MODULE_NAME, "IRC Debug Option: Show events in console", "edit", "0", "Off;On", "0;1", MODERATOR, "bbin_help.txt");
	Settings::add("bbin_debug_all", $MODULE_NAME, "IRC Debug Option: Log everything", "edit", "0", "Off;On", "0;1", MODERATOR, "bbin_help.txt");
	
	//Help files
	$this->help("bbin", $MODULE_NAME, "bbin_help.txt", ALL, "How to use the BBIN plugin", "BBIN");
?>