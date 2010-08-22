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

require_once './core/Util.class.php';
require_once './core/Logger.class.php';

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

require_once './core/Player.class.php';

require_once './core/Command.class.php';
require_once './core/Event.class.php';
require_once './core/Text.class.php';
require_once './core/Settings.class.php';
require_once './core/Whitelist.class.php';
require_once './core/Help.class.php';
require_once './core/Help.class.php';
require_once './core/Buddylist.class.php';
require_once './core/AccessLevel.class.php';

function save_setting_to_db($name, $value, $options, $intoptions, $description, $help) {
	Settings::add($name, 'Basic Settings', $description, 'edit', $value, $options, $intoptions, MODERATOR, $help, 1);
}


class Budabot extends AOChat {

	public $buddyList = array();
	public $public_channels = array(
		"", 'IRRK News Wire', 'OT OOC', 'OT Newbie OOC', 'OT Jpn OOC', 'OT shopping 11-50',
		'Tour Announcements', 'Neu. Newbie OOC', 'Neu. Jpn OOC', 'Neu. shopping 11-50', 'Neu. OOC', 'Clan OOC',
		'Clan Newbie OOC', 'Clan Jpn OOC', 'Clan shopping 11-50', 'OT German OOC', 'Clan German OOC', 'Neu. German OOC'
	);

/*===============================
** Name: __construct
** Constructor of this class.
*/	function __construct($vars) {
		parent::__construct("callback");
		
		$this->vars = $vars;
        $this->name = ucfirst(strtolower($this->vars["name"]));

		//Set startuptime
		$this->vars["startup"] = time();
	}
	
	public function load_settings_from_config(&$settings) {
		save_setting_to_db('symbol', $settings["symbol"], '!;#;*;@;$;+;-', null, 'Prefix for Guild- or Privatechat Commands', null);
		save_setting_to_db('debug', $settings["debug"], "Disabled;Show basic msg's;Show enhanced debug msg's;Show enhanced debug msg's + 1s Delay", '0;1;2;3', 'Show debug messages', null);
		save_setting_to_db('echo', $settings["echo"], 'Disabled;Only Console;Console and Logfiles', '0;1;2' , 'Show messages in console and log them to files', null);
		save_setting_to_db('guild admin level', $settings["guild admin level"], 'President;General;Squad Commander;Unit Commander;Unit Leader;Unit Member;Applicant', '0;1;2;3;4;5;6', 'Min Level for Rank Guildadmin', null);
		save_setting_to_db('default_guild_color', $settings["default_guild_color"], 'color', null, 'Default Guild Color', null);
		save_setting_to_db('default_priv_color', $settings["default_priv_color"], 'color', null, 'Default Private Color', null);
		save_setting_to_db('default_window_color', $settings["default_window_color"], 'color', null, 'Default Window Color', null);
		save_setting_to_db('default_tell_color', $settings["default_tell_color"], 'color', null, 'Default Tell Color', null);
		save_setting_to_db('default_highlight_color', $settings["default_highlight_color"], 'color', null, 'Default Highlight Color', null);
		save_setting_to_db('default_header_color', $settings["default_header_color"], 'color', null, 'Default Header Color', null);
		save_setting_to_db('default_error_color', $settings["default_error_color"], 'color', null, 'Default Error Color', null);
		save_setting_to_db('spam protection', $settings["spam protection"], 'ON;OFF', '1;0', 'Spam Protection for Private Chat', './core/SETTINGS/spam_help.txt');
		save_setting_to_db('default module status', $settings["default module status"], 'ON;OFF', '1;0', 'Default Status for new Modules', './core/SETTINGS/module_status_help.txt');
		save_setting_to_db('max_blob_size', $settings["max_blob_size"], 'number', null, 'Max chars for a window', './core/SETTINGS/max_blob_size_help.txt');
	}
	
	public function init() {
		global $db;
	
		//Create command/event settings table if not exists
		$db->query("CREATE TABLE IF NOT EXISTS cmdcfg_<myname> (`module` VARCHAR(50) NOT NULL, `regex` VARCHAR(255), `file` VARCHAR(255) NOT NULL, `is_core` TINYINT NOT NULL, `cmd` VARCHAR(25) NOT NULL, `tell_status` INT DEFAULT 0, `tell_access_level` INT DEFAULT 0, `guild_status` INT DEFAULT 0, `guild_access_level` INT DEFAULT 0, `priv_status` INT DEFAULT 0, `priv_access_level` INT DEFAULT 0, `description` VARCHAR(50) NOT NULL DEFAULT '', `verify` INT DEFAULT 1)");
		$db->query("CREATE TABLE IF NOT EXISTS eventcfg_<myname> (`module` VARCHAR(50) NOT NULL, `type` VARCHAR(18), `file` VARCHAR(255), `is_core` TINYINT NOT NULL, `description` VARCHAR(50) NOT NULL DEFAULT '', `verify` INT DEFAULT 0, `status` INT DEFAULT 1)");
		$db->query("CREATE TABLE IF NOT EXISTS settings_<myname> (`name` VARCHAR(30) NOT NULL, `module` VARCHAR(50), `mode` VARCHAR(10), `is_core` TINYINT NOT NULL, `setting` VARCHAR(50) DEFAULT '0', `options` VARCHAR(50) Default '0', `intoptions` VARCHAR(50) DEFAULT '0', `description` VARCHAR(50) NOT NULL DEFAULT '', `source` VARCHAR(5), `access_level` INT DEFAULT 0, `help` VARCHAR(60), `verify` INT DEFAULT 1)");
		$db->query("CREATE TABLE IF NOT EXISTS hlpcfg_<myname> (`name` VARCHAR(30) NOT NULL, `module` VARCHAR(50) NOT NULL, `description` VARCHAR(50) NOT NULL DEFAULT '', `file` VARCHAR(255) NOT NULL, `is_core` TINYINT NOT NULL, `access_level` INT DEFAULT 0, `verify` INT Default 1)");

		Logger::log(__FILE__, "Start: Loading CORE MODULES", DEBUG);

		// Load the Core Modules -- SETINGS must be first in case the other modules have settings
		$this->load_core_module("SETTINGS");
		$this->load_core_module("SYSTEM");
		$this->load_core_module("ADMIN");
		$this->load_core_module("BAN");
		$this->load_core_module("HELP");
		$this->load_core_module("CONFIG");
		$this->load_core_module("ORG_ROSTER");
		$this->load_core_module("BASIC_CONNECTED_EVENTS");
		$this->load_core_module("PRIV_TELL_LIMIT");
		
		Logger::log(__FILE__, "End: Loading CORE MODULES", DEBUG);
		
		// Load User Modules
		$this->load_user_modules();
	}
	
	public function connectedEvents() {
		$params = array();
		$params['type'] = "connect";
		Logger::log(__FILE__, "Executing connected events...", DEBUG);
		Event::fire_event($params);
	}

/*===============================
** Name: load_core_module
** Loads a core module
*/	function load_core_module($module_name) {
		Logger::log(__FILE__, "Loading CORE MODULE: $module_name", INFO);
		require "./core/$module_name/$module_name.php";
	}
	
/*===============================
** Name: load_user_modules
** Loads (or reloads) all the user modules
*/	function load_user_modules() {
		global $db;
		
		Logger::log(__FILE__, "Start: Loading USER MODULES", DEBUG);

		//Prepare DB
		$db->query("UPDATE hlpcfg_<myname> SET verify = 0 WHERE `is_core` = 0");
		$db->query("UPDATE cmdcfg_<myname> SET `verify` = 0 WHERE `is_core` = 0");
		$db->query("UPDATE eventcfg_<myname> SET `verify` = 0 WHERE `is_core` = 0");
		$db->query("UPDATE settings_<myname> SET `verify` = 0 WHERE `is_core` = 0");


		//Register modules
		//$this->register_modules();
		
		//Delete old entrys in the DB
		$db->query("DELETE FROM hlpcfg_<myname> WHERE verify = 0 AND `is_core` = 0");
		$db->query("DELETE FROM cmdcfg_<myname> WHERE `verify` = 0 AND `is_core` = 0");
		$db->query("DELETE FROM eventcfg_<myname> WHERE `verify` = 0 AND `is_core` = 0");
		$db->query("DELETE FROM settings_<myname> WHERE `verify` = 0 AND `is_core` = 0");
		
		Logger::log(__FILE__, "End: Loading USER MODULES", DEBUG);
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
						Logger::log(__FILE__, "Loading USER MODULE: $entry", INFO);
						include "./modules/$entry/$entry.php";
					} else {
						Logger::log(__FILE__, "missing module registration file: './modules/$entry/$entry.php'", ERROR);
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
			Logger::log(__FILE__, "No valid Server to connect with! Available dimensions are 1, 2, 3 and 4", ERROR);
		  	sleep(10);
		  	die();
		}

		// Begin the login process
		Logger::log(__FILE__, "Connecting to AO Server...($server)", INFO);
		$this->connect($server, $port);
		sleep(2);
		if ($this->state != "auth") {
			Logger::log(__FILE__, "Connection failed! Please check your Internet connection and firewall", ERROR);
			sleep(10);
			die();
		}

		Logger::log(__FILE__, "Authenticate login data...", INFO);
		$this->authenticate($login, $password);
		sleep(2);
		if ($this->state != "login") {
			Logger::log(__FILE__, "Authentication failed! Please check your username and password", ERROR);
			sleep(10);
			die();
		}

		Logger::log(__FILE__, "Logging in $this->name...", INFO);
		$this->login($this->name);
		sleep(2);
		if ($this->state != "ok") {
			Logger::log(__FILE__, "Logging in of $this->name failed! Please check the character name and dimension", ERROR);
			sleep(10);
			die();
		}

		Logger::log(__FILE__, "All Systems ready....", INFO);
		echo "\n\n";
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
	
		$message = Text::format_message($message);
		$this->send_privgroup($group,Settings::get("default_priv_color").$message);
		if ((Settings::get("guest_relay") == 1 && Settings::get("guest_relay_commands") == 1 && !$disable_relay)) {
			$this->send_group($group, "</font>" . Settings::get("guest_color_channel") . "[Guest]<end> " . Settings::get("guest_color_username") . "$this->name</font>: " . Settings::get("default_priv_color") . "$message</font>");
		}
	}

/*===============================
** Name: send
** Send chat messages back to aochat servers thru aochat.
*/	function send($message, &$who, $disable_relay = false) {
		// for when makeLink generates several pages
		if (is_array($message)) {
			forEach ($message as $page) {
				$this->send($page, $who, $disable_relay);
			}
			return;
		}

		$message = Text::format_message($message);

		// Send
		if ($who instanceof Player) {
			Logger::log_chat("Out. Msg.", $who->name, $message);
			$this->send_tell($who->uid, Settings::get("default_tell_color").$message);
		} else if ($who == 'prv' || $who == 'priv') { // Target is private chat by defult.
			$this->send_privgroup($this->name, Settings::get("default_priv_color").$message);
			if (Settings::get("guest_relay") == 1 && Settings::get("guest_relay_commands") == 1 && !$disable_relay) {
				$this->send_group($this->vars["my guild"], "</font>" . Settings::get("guest_color_channel") . "[Guest]<end> " . Settings::get("guest_color_username") . Text::makeLink($this->name, $this->name, "user")."</font>: " . Settings::get("default_priv_color") . "$message</font>");
			}
		} else if ($who == $this->vars["my guild"] || $who == 'org' || $who == 'guild') {// Target is guild chat.
    		$this->send_group($this->vars["my guild"],Settings::get("default_guild_color").$message);
			if (Settings::get("guest_relay") == 1 && Settings::get("guest_relay_commands") == 1 && !$disable_relay) {
				$this->send_privgroup($this->name, "</font>" . Settings::get("guest_color_channel") . "[{$this->vars["my guild"]}]<end> " . Settings::get("guest_color_username") . Text::makeLink($this->name, $this->name, "user")."</font>: " . Settings::get("default_guild_color") . "$message</font>");
			}
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
				$params = array();
				$params['channel'] = $this->lookup_user($args[0]);

				$player = new Player($args[1]);
				$params['player'] = &$player;

				if ($params['channel'] == $this->vars['name']) {
					$params['type'] = "joinPriv";

					// Add sender to the chatlist.
					Chatlist::joined_chat($player);
					
					Logger::log_chat("Priv Group", $player->name, "joined the channel.");

					// Remove sender if they are /ignored or /banned or if spam filter is blocking them
					if (Settings::is_ignored($player) || Settings::is_banned($player) || Settings::is_spammer($player)) {
						$this->privategroup_kick($player->name);
						return;
					}

					Event::fire_event($params);
				} else {
					$params['type'] = "extJoinPriv";
					Logger::log_chat("Ext Priv", $player->name, "joined chat");
					Event::fire_event($params);
				}
			break;
			case AOCP_PRIVGRP_CLIPART: // 56, Incoming player left private chat
				$params = array();
				$params['channel'] = $this->lookup_user($args[0]);
				
				$player = new Player($args[1]);
				$params['player'] = &$player;

				if ($params['channel'] == $this->vars['name']) {
					$params['type'] = "leavePriv";
					Logger::log_chat("Priv Group", $player->name, "left the channel.");

					// Remove from Chatlist array.
					Chatlist::left_chat($player);
					
					Event::fire_event($params);
				} else {
					$params['type'] = "extLeavePriv";
					Logger::log_chat("Ext Priv", $player->name, "left chat");
					Event::fire_event($params);
				}
			break;
			case AOCP_BUDDY_ADD: // 40, Incoming buddy logon or off
				$params = array();
				$player = new Player($args[0]);
				$params['player'] = &$player;

				$status	= 0 + $args[1];
				$btype = $args[2];

				// store buddy info				
				Buddylist::store_buddy($player, $status, (ord($btype) ? 1 : 0));

				//Ignore Logon/Logoff from other bots or phantom logon/offs
                if (Settings::is_ignored($player)) {
					return;
				}

				// If Status == 0(logoff) if Status == 1(logon)
				if ($status == 0) {
					$params['type'] = "logOff";
					//Logger::log_chat("Buddy", $player->name, "logged off");
					Event::fire_event($params);
				} else if ($status == 1) {
					$params['type'] = "logOn";
					Logger::log_chat("Buddy", $player->name, "logged on");
					Event::fire_event($params);
				}
			break;
			case AOCP_MSG_PRIVATE: // 30, Incoming Msg
				$params = array();
				$params['type'] = 'msg';
				$params['restricted'] = false;

				$player = new Player($args[0]);
				$params['player'] = &$player;

				$params['sendto'] = &$player;
				
				// Removing tell color
				if (preg_match("/^<font color='#([0-9a-f]+)'>(.+)$/i", $args[1], $arr)) {
					$message = $arr[2];
				} else {
					$message = $args[1];
				}

				$message = html_entity_decode($message, ENT_QUOTES);

				Logger::log_chat("Inc. Msg.", $sender, $message);

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

				if (Settings::is_ignored($player) || Settings::is_banned($player) || Settings::is_spammer($player)) {
					Settings::add_spam($player, 20);
					return;
				}

				// Check privatejoin and tell Limits
				if (file_exists('./core/PRIV_TELL_LIMIT/check.php')) {
					require './core/PRIV_TELL_LIMIT/check.php';
				}

				// Events
				if ($params['restricted'] != true) {
					$params['message'] = $message;
					Event::fire_event($params);
				}

				// Commands
				if ($params['restricted'] != true) {
					//Remove the prefix infront if there is one
					if ($message[0] == Settings::get('symbol') && strlen($message) > 1) {
						$message = substr($message, 1);
					}
					
					$params['message'] = $message;
					Command::fire_command($params);
				}
			break;
			case AOCP_PRIVGRP_MESSAGE: // 57, Incoming priv message
				$channel = $this->lookup_user($args[0]);
				$message = $args[2];

				$params = array();
				$player = new Player($args[1]);
				$params['player'] = &$player;
				$params['channel'] = $channel;
				$params['message'] = $message;
				$params['restricted'] = false;
				
				if ($player->name == $this->name) {
					Logger::log_chat("Priv Group", $player->name, $message);
					return;
				}
				if (Settings::is_spammer($player)) {
					return;
				}

				// TODO
				/*
				if ($this->vars['spam protection'] == 1) {
					if ($this->spam[$sender] == 40) $this->send("Error! Your client is sending a high frequency of chat messages. Stop or be kicked.", $player);
					if ($this->spam[$sender] > 60) $this->privategroup_kick($player->uid);
					if (strlen($args[1]) > 400) {
						$this->largespam[$sender] = $this->largespam[$sender] + 1;
						if ($this->largespam[$sender] > 1) $this->privategroup_kick($player->uid);
						if ($this->largespam[$sender] > 0) $this->send("Error! Your client is sending large chat messages. Stop or be kicked.", $player);
					}
				}
				*/

				if ($channel == $this->vars['name']) {
					$params['type'] = "priv";
					$params['sendto'] = 'prv';
					Logger::log_chat("Priv Group", $player->name, $message);
					Event::fire_event($params);

					$msg = "";
					if (!$params['restricted'] && ($message[0] == Settings::get("symbol") && strlen($message) > 1)) {
						//Remove the prefix infront
						$message = substr($message, 1);

						Command::fire_command($params);
					} else {
						$this->send("Error! Unknown command or Access denied! for more info try /tell <myname> help", $sendto);
						Settings::add_spam($player, 20);
					}
				} else {  // ext priv group message
					$params['type'] = "extPriv";
					Logger::log_chat("Ext Priv Group $channel", $player->name, $message);
					Event::fire_event($params);
				}
			break;
			case AOCP_GROUP_MESSAGE: // 65, Public and guild channels
				$message = $args[2];
				$channel = $this->get_gname($args[0]);
			
				$params = array();
				$params['channel'] = $channel;
				$params['restricted'] = false;

				$syntax_error = false;
				$player = new Player($args[1]);
				$params['player'] = &$player;

				//Ignore Messages from Vicinity/IRRK New Wire/OT OOC/OT Newbie OOC...
				if (in_array($channel, $this->public_channels)) {
					return;
				}

				// if it's an extended message
				$em = null;
				if (isset($args['extended_message'])) {
					$em = $args['extended_message'];
					$message = AOExtMsg::get_extended_message($em);
					if ($message == '') {
						$message = $args[2];
					}
				}

				Logger::log_chat($channel, $player->name, $message);

				if ($sender) {
					//Ignore Message that are sent from the bot self
					if ($player->name == $this->name) {
						return;
					}

					//Ignore tells from other bots and banned players
	                if (Settings::is_ignored($player) || Settings::is_banned($player)) {
						return;
					}
				}

				if ($channel == "All Towers" || $channel == "Tower Battle Outcome") {
                    $params['type'] = "towers";
    				Event::fire_event($params);
                } else if ($channel == "Org Msg") {
                    $params['type'] = "orgmsg";
    				Event::fire_event($params);
                } else if ($channel == $this->vars["my guild"]) {
                    $params['type'] = 'guild';
					$params['sendto'] = 'org';

					Event::fire_event($params);
					$msg = "";
					if (!$param['restricted'] && ($message[0] == Settings::get("symbol") && strlen($message) >= 2)) {
						//Remove the prefix infront
						$message = substr($message, 1);
						
						$params['message'] = $message;
						
    					Command::fire_command($params);
					}
				}
			break;
			case AOCP_PRIVGRP_INVITE:  // 50, private group invite
				$params = array();
				$params['type'] = "extJoinPrivRequest"; // Set message type.
				
				$player = new Player($args[1]);
				$params['player'] = &$player;

				Logger::log_chat("Priv Group Invitation", $player->name, " channel invited.");

				Event::fire_event($params);
			break;
		}
	}
}
?>