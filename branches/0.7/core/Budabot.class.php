<?php
   /*
   ** Author: Sebuda/Derroylo (both RK2)
   ** Description: This class provides the basic functions for the bot.
   ** Version: 0.5.9
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 01.10.2005
   ** Date(last modified): 05.02.2007
   **
   ** Copyright (C) 2005, 2006, 2007 Carsten Lohmann and J. Gracik
   **
   ** Licence Infos:
   ** This file is part of Budabot.
   **
   ** Budabot is free software; you can redistribute it and/or modify
   ** it under the terms of the GNU General Public License as published by
   ** the Free Software Foundation; either version 2 of the License, or
   ** (at your option) any later version.
   **
   ** Budabot is distributed in the hope that it will be useful,
   ** but WITHOUT ANY WARRANTY; without even the implied warranty of
   ** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   ** GNU General Public License for more details.
   **
   ** You should have received a copy of the GNU General Public License
   ** along with Budabot; if not, write to the Free Software
   ** Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
   */

require_once './core/AOChat.class.php';
require_once './core/AOChatPacket.class.php';
require_once './core/AOChatQueue.class.php';
require_once './core/AOChat.class.php';
require_once './core/AOExtMsg.class.php';

require_once './core/DB.class.php';
require_once './core/XML.class.php';
require_once './core/OrgXML.class.php';
require_once './core/WhoisXML.class.php';
require_once './core/HistoryXML.class.php';
require_once './core/ServerXML.class.php';

require_once './core/Command.class.php';
require_once './core/Event.class.php';
require_once './core/Text.class.php';
require_once './core/Settings.class.php';
require_once './core/Whitelist.class.php';
require_once './core/Help.class.php';
require_once './core/Help.class.php';
require_once './core/Buddylist.class.php';
require_once './core/AccessLevel.class.php';


class Budabot extends AOChat {

	private $buddyList = array();

/*===============================
** Name: __construct
** Constructor of this class.
*/	function __construct($vars, $settings) {
		parent::__construct("callback");

		global $db;

		$this->settings = $settings;
		$this->vars = $vars;
        $this->name = ucfirst(strtolower($this->vars["name"]));

		//Set startuptime
		$this->vars["startup"] = time();
		
		//Create command/event settings table if not exists
		$db->query("CREATE TABLE IF NOT EXISTS cmdcfg_<myname> (`module` VARCHAR(50) NOT NULL, `regex` VARCHAR(255), `file` VARCHAR(255) NOT NULL, `is_core` TINYINT NOT NULL, `cmd` VARCHAR(25) NOT NULL, `tell_status` INT DEFAULT 0, `tell_access_level` INT DEFAULT 0, `guild_status` INT DEFAULT 0, `guild_access_level` INT DEFAULT 0, `priv_status` INT DEFAULT 0, `priv_access_level` INT DEFAULT 0, `description` VARCHAR(50) NOT NULL DEFAULT '', `verify` INT DEFAULT 1)");
		$db->query("CREATE TABLE IF NOT EXISTS eventcfg_<myname> (`module` VARCHAR(50) NOT NULL, `type` VARCHAR(18), `file` VARCHAR(255), `is_core` TINYINT NOT NULL, `description` VARCHAR(50) NOT NULL DEFAULT '', `verify` INT DEFAULT 0, `status` INT DEFAULT 1)");
		$db->query("CREATE TABLE IF NOT EXISTS settings_<myname> (`name` VARCHAR(30) NOT NULL, `module` VARCHAR(50), `mode` VARCHAR(10), `is_core` TINYINT NOT NULL, `setting` VARCHAR(50) DEFAULT '0', `options` VARCHAR(50) Default '0', `intoptions` VARCHAR(50) DEFAULT '0', `description` VARCHAR(50) NOT NULL DEFAULT '', `source` VARCHAR(5), `access_level` INT DEFAULT 0, `help` VARCHAR(60), `verify` INT DEFAULT 1)");
		$db->query("CREATE TABLE IF NOT EXISTS hlpcfg_<myname> (`name` VARCHAR(30) NOT NULL, `module` VARCHAR(50) NOT NULL, `description` VARCHAR(50) NOT NULL DEFAULT '', `file` VARCHAR(255) NOT NULL, `is_core` TINYINT NOT NULL, `access_level` INT DEFAULT 0, `verify` INT Default 1)");

		// Load the Core Modules -- SETINGS must be first in case the other modules have settings
		if (Settings::get('debug') > 0) print("\n:::::::CORE MODULES::::::::\n");
		$this->load_core_module("SETTINGS");
		$this->load_core_module("SYSTEM");
		$this->load_core_module("ADMIN");
		$this->load_core_module("BAN");
		$this->load_core_module("HELP");
		$this->load_core_module("CONFIG");
		$this->load_core_module("ORG_ROSTER");
		$this->load_core_module("BASIC_CONNECTED_EVENTS");
		$this->load_core_module("PRIV_TELL_LIMIT");
		$this->load_core_module("USER_MODULES");
		
		// Load User Modules
		$this->load_user_modules();
	}

/*===============================
** Name: load_core_module
** Loads a core module
*/	function load_core_module($module_name) {
		if (Settings::get('debug') > 0) {
			print("CORE_MODULE_NAME: $module_name \n");
		}
		include "./core/$module_name/$module_name.php";
	}
	
/*===============================
** Name: load_user_modules
** Loads (or reloads) all the user modules
*/	function load_user_modules() {
		global $db;

		//Prepare DB
		$db->query("UPDATE hlpcfg_<myname> SET verify = 0 WHERE `is_core` = 0");
		$db->query("UPDATE cmdcfg_<myname> SET `verify` = 0 WHERE `is_core` = 0");
		$db->query("UPDATE eventcfg_<myname> SET `verify` = 0 WHERE `is_core` = 0");
		$db->query("UPDATE setting_<myname> SET `verify` = 0 WHERE `is_core` = 0");

		if (Settings::get('debug') > 0) print("\n:::::::User MODULES::::::::\n");

		//Register modules
		$this->register_modules();
		
		//Delete old entrys in the DB
		$db->query("DELETE FROM hlpcfg_<myname> WHERE verify = 0 AND `is_core` = 0");
		$db->query("DELETE FROM cmdcfg_<myname> WHERE `verify` = 0 AND `is_core` = 0");
		$db->query("DELETE FROM eventcfg_<myname> WHERE `verify` = 0 AND `is_core` = 0");
		$db->query("DELETE FROM setting_<myname> WHERE `verify` = 0 AND `is_core` = 0");
	}
	
/*===============================
** Name: register_modules
** Load all Modules
*/	function register_modules() {
		global $db;
		if ($d = dir("./modules")) {
			while (false !== ($entry = $d->read())) {
				if (!is_dir($entry)) {
					// Look for the plugin's ... setup file
					if (file_exists("./modules/$entry/$entry.php")){
						if(Settings::get('debug') > 0) print("MODULE_NAME: $entry.php \n");
						include "./modules/$entry/$entry.php";
					} else {
						echo "Error! missing module registration file: './modules/$entry/$entry.php'\n";
					}
				}
			}
			$d->close();
		}
	}

/*===============================
** Name: connect
** Connect to AO chat servers.
*/	function connectAO($login, $password) {
		// remove any old entries on buddy list
		$buddyList = array();

		echo "\n\n";

		// Choose Server
		if ($this->vars["dimension"] == 1) {
			$server = "chat.d1.funcom.com";
			$port = 7101;
		} else if ($this->vars["dimension"] == 2) {
			$server = "chat.d2.funcom.com";
			$port = 7102;
		} else if ($this->vars["dimension"] == 3) {
			$server = "chat.d3.funcom.com";
			$port = 7103;
		} else if ($this->vars["dimension"] == 4) {
			$server = "chat.dt.funcom.com";
			$port = 7109;
		} else {
			echo "No valid Server to connect with! Available dimensions are 1, 2, 3 and 4.\n";
		  	sleep(10);
		  	die();
		}

		// Begin the login process
		echo "Connecting to AO Server...($server)\n";
		$this->connect($server, $port);
		sleep(2);
		if ($this->state != "auth") {
			echo "Connection failed! Please check your Internet connection and firewall.\n";
			sleep(10);
			die();
		}

		echo "Authenticate login data...\n";
		$this->authenticate($login, $password);
		sleep(2);
		if ($this->state != "login") {
			echo "Authentication failed! Please check your username and password.\n";
			sleep(10);
			die();
		}

		echo "Logging in $this->name...\n";
		$this->login($this->name);
		sleep(2);
		if ($this->state != "ok") {
			echo "Logging in of $this->name failed! Please check the character name and dimension.\n";
			sleep(10);
			die();
		}

		echo "All Systems ready....\n\n\n";
		sleep(2);

		// Set cron timers
		$this->vars["2sec"] 			= time() + Settings::get("CronDelay");
		$this->vars["1min"] 			= time() + Settings::get("CronDelay");
		$this->vars["10mins"] 			= time() + Settings::get("CronDelay");
		$this->vars["15mins"] 			= time() + Settings::get("CronDelay");
		$this->vars["30mins"] 			= time() + Settings::get("CronDelay");
		$this->vars["1hour"] 			= time() + Settings::get("CronDelay");
		$this->vars["24hours"]			= time() + Settings::get("CronDelay");
		$this->vars["15min"] 			= time() + Settings::get("CronDelay");
	}

/*===============================
** Name: ping
** Get next packet info from AOChat
*/	function ping() {
		return $this->wait_for_packet();
	}

	function sendPrivate($message, $group, $disable_relay = false) {
		// for when makeLink generates several pages
		if (is_array($message)) {
			forEach ($message as $page) {
				$this->sendPrivate($page, $group, $disable_relay);
			}
			return;
		}
	
		$message = Text::formatMessage($message);
		$this->send_privgroup($group,Settings::get("default_priv_color").$message);
		if ((Settings::get("guest_relay") == 1 && Settings::get("guest_relay_commands") == 1 && !$disable_relay)) {
			$this->send_group($group, "</font>" . Settings::get("guest_color_channel") . "[Guest]<end> " . Settings::get("guest_color_username") . "$this->name</font>: " . Settings::get("default_priv_color") . "$message</font>");
		}
	}

/*===============================
** Name: send
** Send chat messages back to aochat servers thru aochat.
*/	function send($message, $who = 'prv', $disable_relay = false) {
		// for when makeLink generates several pages
		if (is_array($message)) {
			forEach ($message as $page) {
				$this->send($page, $who, $disable_relay);
			}
			return;
		}

		if ($who == 'guild') {
			$who = 'org';
		} else if ($who == 'priv') {
			$who = 'prv';
		}

		$message = Text::formatMessage($message);

		// Send
		if ($who == 'prv') { // Target is private chat by defult.
			$this->send_privgroup($this->name, Settings::get("default_priv_color").$message);
			if (Settings::get("guest_relay") == 1 && Settings::get("guest_relay_commands") == 1 && !$disable_relay) {
				$this->send_group($this->vars["my guild"], "</font>" . Settings::get("guest_color_channel") . "[Guest]<end> " . Settings::get("guest_color_username") . Text::makeLink($this->name, $this->name, "user")."</font>: " . Settings::get("default_priv_color") . "$message</font>");
			}
		} else if ($who == $this->vars["my guild"] || $who == 'org') {// Target is guild chat.
    		$this->send_group($this->vars["my guild"],Settings::get("default_guild_color").$message);
			if (Settings::get("guest_relay") == 1 && Settings::get("guest_relay_commands") == 1 && !$disable_relay) {
				$this->send_privgroup($this->name, "</font>" . Settings::get("guest_color_channel") . "[{$this->vars["my guild"]}]<end> " . Settings::get("guest_color_username") . Text::makeLink($this->name, $this->name, "user")."</font>: " . Settings::get("default_guild_color") . "$message</font>");
			}
		} else if ($this->get_uid($who) != NULL) {// Target is a player.
    		$this->send_tell($who, Settings::get("default_tell_color").$message);
			// Echo
			if (Settings::get('echo') >= 1) newLine("Out. Msg.", $who, $message, Settings::get('echo'));
		} else { // Public channels that are not myguild.
	    	$this->send_group($who, Settings::get("default_guild_color").$message);
		}
	}

/*===========================================================================================
** Name: processCallback
** Proccess all incoming messages that bot recives
*/	function processCallback($type, $args) {
		global $db;

		switch ($type) {
			case AOCP_GROUP_ANNOUNCE: // 60
				$b = unpack("C*", $args[0]);
				if ($b[1]==3) {
					$this->vars["my guild id"] = $b[2]*256*256*256 + $b[3]*256*256 + $b[4]*256 + $b[5];
				}
			break;
			case AOCP_PRIVGRP_CLIJOIN: // 55, Incoming player joined private chat
				$sender	= $this->lookup_user($args[1]);// Get Name
				$channel = $this->lookup_user($args[0]);// Get Name
				$char_id = $args[1];
				
				if ($channel == $this->vars['name']) {
					$type = "joinPriv";

					// Add sender to the chatlist.
					$this->chatlist[$char_id] = new WhoisXML($sender);
					
					// Echo
					if (Settings::get('echo') >= 1) newLine("Priv Group", $sender, "joined the channel.", Settings::get('echo'));

					// Remove sender if they are /ignored or /banned or They gone above spam filter
					if (Settings::is_ignored($sender) || $this->banlist[$sender]["name"] == $sender || $this->spam[$sender] > 100) {
						$this->privategroup_kick($sender);
						return;
					}

					// Check files, for all 'player joined channel events'.
					$events = Event::find_active_events($type);
					if ($events != NULL) {
						forEach ($events as $event) {
							include $event->file;
						}
					}
				} else {
					$type = "extJoinPriv";
				}
			break;
			case AOCP_PRIVGRP_CLIPART: // 56, Incoming player left private chat
				$sender	= $this->lookup_user($args[1]); // Get Name
				$channel = $this->lookup_user($args[0]);// Get Name
				$char_id = $args[1];

				if ($channel == $this->vars['name']) {
					$type = "leavePriv";

					// Echo
					if (Settings::get('echo') >= 1) newLine("Priv Group", $sender, "left the channel.", Settings::get('echo'));

					// Remove from Chatlist array.
					unset($this->chatlist[$sender]);
					
					// Remove sender if they are /ignored or /banned or They gone above spam filter
					if (Settings::is_ignored($sender) || $this->banlist[$sender]["name"] == $sender || $this->spam[$sender] > 100) {
						return;
					}
					
					// Check files, for all 'player left channel events'.
					$events = Event::find_active_events($type);
					if ($events != NULL) {
						forEach ($events as $event) {
							include $event->file;
						}
					}
				} else {
					$type = "extLeavePriv";
				}
			break;
			case AOCP_BUDDY_ADD: // 40, Incoming buddy logon or off
				// Basic packet data
				$sender	= $this->lookup_user($args[0]);
				$char_id = $args[0];
				$status	= 0 + $args[1];
				$btype = $args[2];
				
				// store buddy info
				$this->buddyList[$char_id]['uid'] = $char_id;
				$this->buddyList[$char_id]['name'] = $sender;
				$this->buddyList[$char_id]['online'] = $status;
				$this->buddyList[$char_id]['known'] = (ord($btype) ? 1 : 0);

				//Ignore Logon/Logoff from other bots or phantom logon/offs
                if (Settings::is_ignored($sender) || $sender == "") {
					return;
				}

				// If Status == 0(logoff) if Status == 1(logon)
				if ($status == 0) {
					$type = "logOff"; // Set message type
					
					// Echo
					//if (Settings::get('echo') >= 1) newLine("Buddy", $sender, "logged off", Settings::get('echo'));

					// Check files, for all 'player logged off events'
					$events = Event::find_active_events($type);
					if ($events != NULL) {
						forEach ($events as $event) {
							include $event->file;
						}
					}
				} else if ($status == 1) {
					$type = "logOn"; // Set Message Type
					
					// Echo
					if (Settings::get('echo') >= 1) newLine("Buddy", $sender, "logged on", Settings::get('echo'));

					// Check files, for all 'player logged on events'.
					$events = Event::find_active_events($type);
					if ($events != NULL) {
						forEach ($events as $event) {
							include $event->file;
						}
					}
				}
			break;
			case AOCP_MSG_PRIVATE: // 30, Incoming Msg
				$type = "msg"; // Set message type.
				$sender	= $this->lookup_user($args[0]);
				$char_id = $args[0];
				$sendto = $sender;
				
				// Removing tell color
				if (preg_match("/^<font color='#([0-9a-f]+)'>(.+)$/i", $args[1], $arr)) {
					$message = $arr[2];
				} else {
					$message = $args[1];
				}

				$message = html_entity_decode($message, ENT_QUOTES);

				// Echo
				if (Settings::get('echo') >= 1) newLine("Inc. Msg.", $sender, $message, Settings::get('echo'));

				// AFK/bot check
				if (preg_match("/^$sender is AFK/si", $message, $arr)) {
					return;
				} else if (preg_match("/^I am away from my keyboard right now/si", $message)) {
					return;
				} else if (preg_match("/^Unknown command/si", $message, $arr)) {
					return;
				} else if (preg_match("/^I am responding/si", $message, $arr)) {
					return;
				} else if (preg_match("/^I only listen/si", $message, $arr)) {
					return;
				} else if (preg_match("/^Error!/si", $message, $arr)) {
					return;
				}

				if (Settings::is_ignored($sender) || $this->banlist[$sender]["name"] == $sender || ($this->spam[$sender] > 100 && $this->vars['spam protection'] == 1)) {
					$this->spam[$sender] += 20;
					return;
				}

				//Remove the prefix infront if there is one
				if ($message[0] == Settings::get('symbol') && strlen($message) > 1) {
					$message = substr($message, 1);
				}

				// Check privatejoin and tell Limits
				if (file_exists('./core/PRIV_TELL_LIMIT/check.php')) {
					include './core/PRIV_TELL_LIMIT/check.php';
				}

				// Events
				$events = Event::find_active_events($type);
				if ($restricted != true && $events != NULL) {
					forEach ($events as $event) {
						include $event->file;
					}
				}

				// Admin Code
				if ($restricted != true) {
					// Break down in to words.
					$words	= split(' ', strtolower($message));
					$access_level = $this->tellCmds[$words[0]]['access_level'];
					$filename = $this->tellCmds[$words[0]]['filename'];

				  	//Check if a subcommands for this exists
				  	if ($this->subcommands[$filename][$type]) {
					    if (preg_match("/^{$this->subcommands[$filename][$type]["cmd"]}$/i", $message)) {
							$access_level = $this->subcommands[$filename][$type]['access_level'];
						}
					}

					$user_access_level = AccessLevel::get_user_access_level($sender);
					if ($user_access_level > $access_level) {
						$restricted = true;
					}
				}

				// Upload Command File or return error message
				if ($restricted == true || $filename == "") {
					$this->send("Error! Unknown command or Access denied! for more info try /tell <myname> help", $sendto);
					$this->spam[$sender] = $this->spam[$sender] + 20;
					return;
				} else {
 				    $syntax_error = false;
 				    $msg = "";
					include $filename;
					if ($syntax_error == true) {
						if (($output = Help::find($sender, $words[0])) !== FALSE) {
							$this->send("Error! Check your syntax " . $output, $sendto);
						} else {
							$this->send("Error! Check your syntax or for more info try /tell <myname> help", $sendto);
						}
					}
					$this->spam[$sender] = $this->spam[$sender] + 10;
				}
			break;
			case AOCP_PRIVGRP_MESSAGE: // 57, Incoming priv message
				$sender	= $this->lookup_user($args[1]);
				$char_id = $args[1];
				$sendto = 'prv';
				$channel = $this->lookup_user($args[0]);
				$message = $args[2];
				$restricted = false;
				if ($sender == $this->name) {
					if (Settings::get('echo') >= 1) newLine("Priv Group", $sender, $message, Settings::get('echo'));
					return;
				}
				if ($this->banlist[$sender]["name"] == $sender) {
					return;
				}

				if ($this->vars['spam protection'] == 1) {
					if ($this->spam[$sender] == 40) $this->send("Error! Your client is sending a high frequency of chat messages. Stop or be kicked.", $sender);
					if ($this->spam[$sender] > 60) $this->privategroup_kick($sender);
					if (strlen($args[1]) > 400) {
						$this->largespam[$sender] = $this->largespam[$sender] + 1;
						if ($this->largespam[$sender] > 1) $this->privategroup_kick($sender);
						if ($this->largespam[$sender] > 0) $this->send("Error! Your client is sending large chat messages. Stop or be kicked.", $sender);
					}
				}

				if ($channel == $this->vars['name']) {

					$type = "priv";

					// Echo
					if (Settings::get('echo') >= 1) newLine("Priv Group", $sender, $message, Settings::get('echo'));

					$events = Event::find_active_events($type);
					if ($events != NULL) {
						forEach ($events as $event) {
							include $event->file;
						}
					}

					$msg = "";
					if (!$restriced && (($message[0] == Settings::get("symbol") && strlen($message) >= 2) || preg_match("/^(afk|brb)/i", $message, $arr))) {
						if ($message[0] == Settings::get("symbol")) {
							$message 	= substr($message, 1);
						}
						$words		= split(' ', strtolower($message));
						$access_level= $this->privCmds[$words[0]]["access_level"];
						$filename 	= $this->privCmds[$words[0]]["filename"];

						//Check if a subcommands for this exists
						if ($this->subcommands[$filename][$type]) {
							if (preg_match("/^{$this->subcommands[$filename][$type]["cmd"]}$/i", $message)) {
								$access_level = $this->subcommands[$filename][$type]["access_level"];
							}
						}


						$user_access_level = AccessLevel::get_user_access_level($sender);
						if ($user_access_level > $access_level) {
							$restricted = true;
						} else {
							if ($filename != "") {
								include $filename;
							}
						}
					} else {
						$this->spam[$sender] = $this->spam[$sender] + 10;
					}
				
				} else {  // ext priv group message
					
					$type = "extPriv";
					
					if (Settings::get('echo') >= 1) newLine("Ext Priv Group $channel", $sender, $message, Settings::get('echo'));
					
					$events = Event::find_active_events($type);
					if ($events != NULL) {
						forEach ($events as $event) {
							include $event->file;
						}
					}
				}
			break;
			case AOCP_GROUP_MESSAGE: // 65, Public and guild channels
				$syntax_error = false;
				$sender	 = $this->lookup_user($args[1]);
				$char_id = $args[1];
				$message = $args[2];
				$channel = $this->get_gname($args[0]);

				//Ignore Messages from Vicinity/IRRK New Wire/OT OOC/OT Newbie OOC...
				$channelsToIgnore = array("", 'IRRK News Wire', 'OT OOC', 'OT Newbie OOC', 'OT Jpn OOC', 'OT shopping 11-50',
					'Tour Announcements', 'Neu. Newbie OOC', 'Neu. Jpn OOC', 'Neu. shopping 11-50', 'Neu. OOC', 'Clan OOC',
					'Clan Newbie OOC', 'Clan Jpn OOC', 'Clan shopping 11-50', 'OT German OOC', 'Clan German OOC', 'Neu. German OOC');

				if (in_array($channel, $channelsToIgnore)) {
					return;
				}

				// if it's an extended message
				$em = null;
				if (isset($args['extended_message'])) {
					$em = $args['extended_message'];
					$db->query("SELECT category, entry, message FROM mmdb_data WHERE category = $em->category AND entry = $em->instance");
					if ($row = $db->fObject()) {
						$message = vsprintf($row->message, $em->args);
					} else {
						echo "Error: cannot find extended message with category: '$em->category' and instance: '$em->instance'\n";
					}
				}

				if (Settings::get('echo') >= 1) newLine($channel, $sender, $message, Settings::get('echo'));

				if ($sender) {
					//Ignore Message that are sent from the bot self
					if ($sender == $this->name) {
						return;
					}

					//Ignore tells from other bots
	                if (Settings::is_ignored($sender)) {
						return;
					}

					if ($this->banlist[$sender]["name"] == $sender) {
						return;
					}
				}

				if ($channel == "All Towers" || $channel == "Tower Battle Outcome") {
                    $type = "towers";
    				$events = Event::find_active_events($type);
					if ($events != NULL) {
						forEach ($events as $event) {
							include $event->file;
						}
					}
                    return;
                } else if ($channel == "Org Msg") {
                    $type = "orgmsg";
    				$events = Event::find_active_events($type);
					if ($events != NULL) {
						forEach ($events as $event) {
							include $event->file;
						}
					}
                    return;
                } else if ($channel == $this->vars["my guild"]) {
                    $type = "guild";
					$sendto = 'org';
					$events = Event::find_active_events($type);
					if ($events != NULL) {
						forEach ($events as $event) {
							include $event->file;
						}
					}
					$msg = "";
					if (!$restriced && (($message[0] == Settings::get("symbol") && strlen($message) >= 2) || preg_match("/^(afk|brb)/i", $message, $arr))) {
						if ($message[0] == Settings::get("symbol")) {
							$message 	= substr($message, 1);
						}
    					$words		= split(' ', strtolower($message));
						$access_level= $this->guildCmds[$words[0]]["access_level"];
						$filename 	= $this->guildCmds[$words[0]]["filename"];

					  	//Check if a subcommands for this exists
					  	if ($this->subcommands[$filename][$type]) {
						    if (preg_match("/^{$this->subcommands[$filename][$type]["cmd"]}$/i", $message)) {
								$access_level = $this->subcommands[$filename][$type]["access_level"];
							}
						}

						$user_access_level = AccessLevel::get_user_access_level($sender);
						if ($user_access_level > $access_level) {
							$this->send("Error! You do not have access to this command.", "guild");
						} else {
							if ($filename != "") {
								include $filename;
							}
						}

						//Shows syntax errors to the user
						if ($syntax_error == true) {
							if (($output = Help::find($sender, $words[0])) !== FALSE) {
								$this->send("Error! " . $output, $sendto);
							} else {
								$this->send("Error! Check your syntax or for more info try /tell <myname> help", $sendto);
							}
						}
					}
				}
			break;
			case AOCP_PRIVGRP_INVITE:  // 50, private group invite
				$type = "extJoinPrivRequest"; // Set message type.
				$sender = $this->lookup_user($args[0]);
				$char_id = $args[0];

				// Echo
				if (Settings::get('echo') >= 1) newLine("Priv Group Invitation", $sender, " channel invited.", Settings::get('echo'));

				$events = Event::find_active_events($type);
				if ($events != NULL) {
					forEach ($events as $event) {
						include $event->file;
					}
				}
                return;
			break;
		}
	}

	function verifyFilename($filename) {
		//Replace all \ characters with /
		$filename = str_replace("\\", "/", $filename);

		if (!$this->verifyNameConvention($filename)) {
			return FALSE;
		}

		//check if the file exists
	    if (file_exists("./core/$filename")) {
	        return "./core/$filename";
    	} else if (file_exists("./modules/$filename")) {
        	return "./modules/$filename";
	    } else {
	     	return FALSE;
	    }
	}

	function verifyNameConvention($filename) {
		preg_match("/^([0-9a-z_]+)\\/([0-9a-z_]+)\\.php$/i", $filename, $arr);
		if ($arr[2] == strtolower($arr[2])) {
			return TRUE;
		} else {
			echo "Warning: $filename does not match the nameconvention(All php files needs to be in lowercases except loading files)!\n";
			return FALSE;
		}
	}
}
?>