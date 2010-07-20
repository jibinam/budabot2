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

class Budabot extends AOChat {

	private $buddyList = array();
	
	// Events
	private $subcommands;
	private $tellCmds;
	private $privCmds;
	private $guildCmds;
	private $towers;
	private $orgmsg;
	private $privMsgs;
	private $privChat;
	private $guildChat;
	private $joinPriv;
	private $leavePriv;
	private $logOn;
	private $logOff;
	private $_connect;
	private $_2sec;
	private $_1min;
	private $_10mins;
	private $_15mins;
	private $_30mins;
	private $_1hour;
	private $_24hrs;

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
		$db->query("CREATE TABLE IF NOT EXISTS cmdcfg_<myname> (`module` VARCHAR(50), `regex` VARCHAR(255), `file` VARCHAR(255), is_core TINYINT NOT NULL, `cmd` VARCHAR(25), `tell_status` INT DEFAULT 0, `tell_access_level` INT DEFAULT 0, `guild_status` INT DEFAULT 0, `guild_access_level` INT DEFAULT 0, `priv_status` INT DEFAULT 0, `priv_access_level` INT DEFAULT 0, `description` VARCHAR(50) DEFAULT 'none', `verify` INT DEFAULT 1)");
		$db->query("CREATE TABLE IF NOT EXISTS eventcfg_<myname> (`module` VARCHAR(50), `type` VARCHAR(10), `file` VARCHAR(255), is_core TINYINT NOT NULL, `description` VARCHAR(50) DEFAULT 'none', `verify` INT DEFAULT 0, `status` INT DEFAULT 1");
		$db->query("CREATE TABLE IF NOT EXISTS settings_<myname> (`name` VARCHAR(30) NOT NULL, `module` VARCHAR(50), `mode` VARCHAR(10), `setting` VARCHAR(50) Default '0', `options` VARCHAR(50) Default '0', `intoptions` VARCHAR(50) DEFAULT '0', `description` VARCHAR(50), `source` VARCHAR(5), `access_level` INT DEFAULT 0, `help` VARCHAR(60), `verify` INT DEFAULT 1)");
		$db->query("CREATE TABLE IF NOT EXISTS hlpcfg_<myname> (`name` VARCHAR(30) NOT NULL, `module` VARCHAR(50), `description` VARCHAR(50), `file` VARCHAR(255), is_core TINYINT NOT NULL, `access_level` INT DEFAULT 0, `verify` INT Default 1)");
		
		// Events
		/*
		unset($this->tellCmds);
		unset($this->privCmds);
		unset($this->guildCmds);
		unset($this->towers);
		unset($this->orgmsg);
		unset($this->privMsgs);
		unset($this->privChat);
		unset($this->guildChat);
		unset($this->joinPriv);
		unset($this->leavePriv);
		unset($this->logOn);
		unset($this->logOff);
		unset($this->_2sec);
		unset($this->_1min);
		unset($this->_10mins);
		unset($this->_15mins);
		unset($this->_30mins);
		unset($this->_1hour);
		unset($this->_24hrs);
		unset($this->_connect);
		*/

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
		$db->query("UPDATE hlpcfg_<myname> SET verify = 0");
		$db->query("UPDATE cmdcfg_<myname> SET `verify` = 0");
		$db->query("UPDATE eventcfg_<myname> SET `verify` = 0");
		$db->query("UPDATE setting_<myname> SET `verify` = 0");

		if (Settings::get('debug') > 0) print("\n:::::::User MODULES::::::::\n");

		//Register modules
		$this->register_modules();
		
		//Delete old entrys in the DB
		$db->query("DELETE FROM hlpcfg_<myname> WHERE verify = 0");
		$db->query("DELETE FROM cmdcfg_<myname> WHERE `verify` = 0");
		$db->query("DELETE FROM eventcfg_<myname> WHERE `verify` = 0");
		$db->query("DELETE FROM setting_<myname> WHERE `verify` = 0");

		//Load active commands
		if (Settings::get('debug') > 0) print("\nSetting up commands.\n");
		$this->load_commands();

		//Load active events
		if (Settings::get('debug') > 0) print("\nSetting up events.\n");
		$this->load_events();
		
		//Load active events
		if (Settings::get('debug') > 0) print("\nLoading settings.\n");
		$this->load_settings();
	}
	
/*===============================
** Name: register_modules
** Load all Modules
*/	function register_modules() {
		global $db;
		if ($d = dir("./modules")) {
			while (false !== ($entry = $d->read())) {
				if (!is_dir("$entry")) {
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
** Name: load_commands
**  Load the Commands that are set as active
*/	function load_commands() {
	  	global $db;

		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `status` = 1");
		$data = $db->fObject("all");
		forEach ($data as $row) {
			if ($row->tell_status == 1) {
				$this->tellCmds[$row->name] = $row;
			}
			if ($row->guild_status == 1) {
				$this->guildCmds[$row->name] = $row;
			}
			if ($row->priv_status == 1) {
				$this->privCmds[$row->name] = $row;
			}
		}
	}

/*===============================
** Name: load_events
**  Load the Events that are set as active
*/	function load_events() {
	  	global $db;

		$db->query("SELECT * FROM eventcfg_<myname> WHERE `status` = 1");
		$data = $db->fObject("all");
		forEach ($data as $row) {
			$this->regevent($row->type, $row->file);
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

/*===============================
** Name: connectedEvents
** Execute Events that needs to be executed right after login
*/	function connectedEvents() {
		global $db;

		// Check files, for all 'connect events'.
		forEach ($this->_connect as $filename) {
			include $filename;
		}
	}

/*===============================
** Name: formatMessage
** Formats an outgoing message with correct colors, replaces values, etc
*/	function formatMessage($message) {
		// Color
		$message = str_replace("<header>", Settings::get('default_header_color'), $message);
		$message = str_replace("<error>", Settings::get('default_error_color'), $message);
		$message = str_replace("<highlight>", Settings::get('default_highlight_color'), $message);
		$message = str_replace("<black>", "<font color='#000000'>", $message);
		$message = str_replace("<white>", "<font color='#FFFFFF'>", $message);
		$message = str_replace("<yellow>", "<font color='#FFFF00'>", $message);
		$message = str_replace("<blue>", "<font color='#8CB5FF'>", $message);
		$message = str_replace("<green>", "<font color='#00DE42'>", $message);
		$message = str_replace("<white>", "<font color='#FFFFFF'>", $message);
		$message = str_replace("<red>", "<font color='#ff0000'>", $message);
		$message = str_replace("<orange>", "<font color='#FCA712'>", $message);
		$message = str_replace("<grey>", "<font color='#C3C3C3'>", $message);
		$message = str_replace("<cyan>", "<font color='#00FFFF'>", $message);

		$message = str_replace("<myname>", $this->name, $message);
		$message = str_replace("<tab>", "    ", $message);
		$message = str_replace("<end>", "</font>", $message);
		$message = str_replace("<symbol>", Settings::get("symbol") , $message);
		
		$message = str_replace("<neutral>", "<font color='#EEEEEE'>", $message);
		$message = str_replace("<omni>", "<font color='#00FFFF'>", $message);
		$message = str_replace("<clan>", "<font color='#F79410'>", $message);
		$message = str_replace("<unknown>", "<font color='#FF0000'>", $message);

		return $message;
	}

	function sendPrivate($message, $group, $disable_relay = false) {
		// for when makeLink generates several pages
		if (is_array($message)) {
			forEach ($message as $page) {
				$this->sendPrivate($page, $group, $disable_relay);
			}
			return;
		}
	
		$message = $this->formatMessage($message);
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

		$message = $this->formatMessage($message);

		// Send
		if ($who == 'prv') { // Target is private chat by defult.
			$this->send_privgroup($this->name, Settings::get("default_priv_color").$message);
			if (Settings::get("guest_relay") == 1 && Settings::get("guest_relay_commands") == 1 && !$disable_relay) {
				$this->send_group($this->vars["my guild"], "</font>" . Settings::get("guest_color_channel"] . "[Guest]<end> " . Settings::get("guest_color_username") . Links::makeLink($this->name, $this->name, "user")."</font>: " . Settings::get("default_priv_color") . "$message</font>");
			}
		} else if ($who == $this->vars["my guild"] || $who == 'org') {// Target is guild chat.
    		$this->send_group($this->vars["my guild"],Settings::get("default_guild_color").$message);
			if (Settings::get("guest_relay") == 1 && Settings::get("guest_relay_commands") == 1 && !$disable_relay) {
				$this->send_privgroup($this->name, "</font>" . Settings::get("guest_color_channel") . "[{$this->vars["my guild"]}]<end> " . Settings::get("guest_color_username") . Links::makeLink($this->name, $this->name, "user")."</font>: " . Settings::get("default_guild_color") . "$message</font>");
			}
		} else if ($this->get_uid($who) != NULL) {// Target is a player.
    		$this->send_tell($who, Settings::get("default_tell_color").$message);
			// Echo
			if (Settings::get('echo') >= 1) newLine("Out. Msg.", $who, $message, Settings::get('echo'));
		} else { // Public channels that are not myguild.
	    	$this->send_group($who, Settings::get("default_guild_color").$message);
		}
	}

/*===============================
** Name: Command
** 	Register a command
*/	function command($type, $module, $filename, $command, $access_level = ALL, $description = '') {
		global $db;

		if (!$this->processCommandArgs($type, $access_level)) {
			echo "invalid args for command '$command'!!\n";
			return;
		}

		$command = strtolower($command);
		$description = str_replace("'", "''", $description);

		for ($i = 0; $i < count($type); $i++) {
			if (Settings::get('debug') > 1) print("Adding Command to list:($command) File:($filename)\n");
			if (Settings::get('debug'] > 1) print("                 Admin:({$access_level[$i]}) Type:({$type[$i)})\n");
			if (Settings::get('debug') > 2) sleep(1);
			
			if ($this->existing_commands[$type[$i]][$command] == true) {
				$db->query("UPDATE cmdcfg_<myname> SET `module` = '$module', `verify` = 1, `file` = '$filename', `description` = '$description' WHERE `cmd` = '$command' AND `type` = '{$type[$i]}'");
			} else {
				$db->query("INSERT INTO cmdcfg_<myname> (`module`, `type`, `file`, `cmd`, `access_level`, `description`, `verify`, `cmdevent`, `status`) VALUES ('$module', '{$type[$i]}', '$filename', '$command', $access_level, '$description', 1, 'cmd', '".Settings::get("default module status")."')");
			}
		}
	}

/*===============================
** Name: regcommand
**  Sets an command as active
*/	function regcommand($type, $module, $filename, $command, $access_level = ALL) {
		global $db;
		
		$filename = $module . '/' . $filename;
		$command = strtolower($command);

	  	if (Settings::get('debug') > 1) print("Activate Command:($command) Admin Type:($access_level)\n");
		if (Settings::get('debug') > 1) print("            File:($filename) Type:($type)\n");
		if (Settings::get('debug') > 2) sleep(1);

		//Check if the file exists
		if (($filename = $this->verifyFilename($filename)) === FALSE) {
			echo "Error in registering the file '$filename' for command '$command'. The file doesn't exists!\n";
			return;
		}

		switch ($type) {
			case "msg":
				if ($this->tellCmds[$command]["filename"] == "") {
					$this->tellCmds[$command]["filename"] = $filename;
					$this->tellCmds[$command]["access_level"] = $access_level;
				}
			break;
			case "priv":
				if ($this->privCmds[$command]["filename"] == "") {
					$this->privCmds[$command]["filename"] = $filename;
					$this->privCmds[$command]["access_level"] = $access_level;
				}
			break;
			case "guild":
				if ($this->guildCmds[$command]["filename"] == "") {
					$this->guildCmds[$command]["filename"] = $filename;
					$this->guildCmds[$command]["access_level"] = $access_level;
				}
			break;
		}
	}

/*===============================
** Name: unregcommand
** 	Deactivates an command
*/	function unregcommand($type, $module, $filename, $command) {
  		global $db;
		$command = strtolower($command);

	  	if (Settings::get('debug') > 1) print("Deactivate Command:($command) File:($filename)\n");
		if (Settings::get('debug') > 1) print("              Type:($type)\n");
		if (Settings::get('debug') > 2) sleep(1);

		switch ($type) {
			case "msg":
				unset($this->tellCmds[$command]);
				break;
			case "priv":
				unset($this->privCmds[$command]);
				break;
			case "guild":
				unset($this->guildCmds[$command]);
				break;
			default:
				echo "ERROR! Invalid type: '$type' for command: '$command' in file: '$filename'\n";
		}

		//Deactivate Events that are asssigned to this command
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `dependson` = '$command' AND `cmdevent` = 'event' AND `type` != 'setup'");
  		while ($row = $db->fObject()) {
  		  	$this->unregevent($row->type, $row->module, $row->file);
		}
	}

/*===============================
** Name: processCommandType
** 	Returns a command type in the proper format
*/	function processCommandArgs(&$type, &$access_level) {
		if ($type == "") {
			$type = array("msg", "priv", "guild");
		} else {
			$type = explode(' ', $type);
		}

		$admin = explode(' ', $access_level);
		if (count($admin) == 1) {
			$admin = array_fill(0, count($type), $admin[0]);
		} else if (count($admin) != count($type)) {
			echo "ERROR! the number of type arguments does not equal the number of admin arguments for command/subcommand registration!";
			return false;
		}
		return true;
	}

/*===============================
** Name: Subcommand
** 	Register a subcommand
*/	function subcommand($type, $module, $filename, $command, $access_level = ALL, $dependson, $description = 'none') {
		global $db;

		if (!$this->processCommandArgs($type, $access_level)) {
			echo "invalid args for subcommand '$command'!!\n";
			return;
		}

		$command = strtolower($command);
	  	
		if ($command != NULL) // Change commands to lower case.
			$command = strtolower($command);

		for ($i = 0; $i < count($type); $i++) {
			if (Settings::get('debug') > 1) print("Adding Subcommand to list:($command) File:($filename)\n");
			if (Settings::get('debug'] > 1) print("                    Admin:($access_level[$i]) Type:({$type[$i)})\n");
			if (Settings::get('debug') > 2) sleep(1);
			
			if ($this->existing_subcmds[$type[$i]][$command] == true) {
				$db->query("UPDATE cmdcfg_<myname> SET `module` = '$module', `verify` = 1, `file` = '$filename', `description` = '$description', `dependson` = '$dependson' WHERE `cmd` = '$command' AND `type` = '{$type[$i]}'");
			} else {
				$db->query("INSERT INTO cmdcfg_<myname> (`module`, `type`, `file`, `cmd`, `access_level`, `description`, `verify`, `cmdevent`, `dependson`, `status`) VALUES ('$module', '{$type[$i]}', '$filename', '$command', $access_level, '$description', 1, 'subcmd', '$dependson', '".Settings::get("default module status")."')");
			}
		}
	}

/*===============================
** Name: event
**  Registers an event
*/	function event($type, $module, $filename, $dependson = 'none', $desc = 'none') {
		global $db;

	  	if (Settings::get('debug') > 1) print("Adding Event to list:($type) File:($filename)\n");
		if (Settings::get('debug') > 2) sleep(1);

		if ($dependson == "none" && Settings::get("default module status") == 1) {
			$status = 1;
		} else {
			$status = 0;
		}

		if ($this->existing_events[$type][$filename] == true) {
		  	$db->query("UPDATE cmdcfg_<myname> SET `dependson` = '$dependson', `verify` = 1, `description` = '$desc' WHERE `type` = '$type' AND `cmdevent` = 'event' AND `file` = '$filename' AND `module` = '$module'");
		} else {
		  	$db->query("INSERT INTO cmdcfg_<myname> (`module`, `cmdevent`, `type`, `file`, `verify`, `dependson`, `description`, `status`) VALUES ('$module', 'event', '$type', '$filename', '1', '$dependson', '$desc', '$status')");
		}
	}

/*===============================
** Name: regevent
**  Sets an event as active
*/	function regevent($type, $module, $filename) {
		global $db;
		
		$filename = $module . '/' . $filename;

	  	if (Settings::get('debug') > 1) print("Activating Event:($type) File:($filename)\n");
		if (Settings::get('debug') > 2) sleep(1);

		//Check if the file exists
		if (($filename = $this->verifyFilename($filename)) === FALSE) {
			echo "Error in registering the file '$filename' for eventtype '$type'. The file doesn't exists!\n";
			return;
		}

		switch ($type) {
			case "towers":
				if (!in_array($filename, $this->towers)) {
					$this->towers[] = $filename;
				}
				break;
			case "orgmsg":
				if (!in_array($filename, $this->orgmsg)) {
					$this->orgmsg[] = $filename;
				}
				break;
			case "msg":
				if (!in_array($filename, $this->privMsgs)) {
					$this->privMsgs[] = $filename;
				}
				break;
			case "priv":
				if (!in_array($filename, $this->privChat)) {
					$this->privChat[] = $filename;
				}
				break;
			case "extPriv":
				if (!in_array($filename, $this->extPrivChat)) {
					$this->extPrivChat[] = $filename;
				}
				break;
			case "guild":
				if (!in_array($filename, $this->guildChat)) {
					$this->guildChat[] = $filename;
				}
				break;
			case "joinPriv":
				if (!in_array($filename, $this->joinPriv)) {
					$this->joinPriv[] = $filename;
				}
				break;
			case "extJoinPriv":
				if (!in_array($filename, $this->extJoinPriv)) {
					$this->extJoinPriv[] = $filename;
				}
				break;
			case "leavePriv":
				if (!in_array($filename, leavePriv)) {
					$this->leavePriv[] = $filename;
				}
				break;
			case "extLeavePriv":
				if (!in_array($filename, extLeavePriv)) {
					$this->extLeavePriv[] = $filename;
				}
				break;
			case "extJoinPrivRequest":
				if (!in_array($filename, $this->extJoinPrivRequest)) {
					$this->extJoinPrivRequest[] = $filename;
				}
				break;
			case "extKickPriv":
				if (!in_array($filename, $this->extKickPriv)) {
					$this->extKickPriv[] = $filename;
				}
				break;
			case "logOn":
				if (!in_array($filename, $this->logOn)) {
					$this->logOn[] = $filename;
				}
				break;
			case "logOff":
				if (!in_array($filename, $this->logOff)) {
					$this->logOff[] = $filename;
				}
				break;
			case "2sec":
				if (!in_array($filename, $this->_2sec)) {
					$this->_2sec[] = $filename;
				}
				break;
			case "1min":
				if (!in_array($filename, $this->_1min)) {
					$this->_1min[] = $filename;
				}
				break;
			case "10mins":
				if (!in_array($filename, $this->_10mins)) {
					$this->_10mins[] = $filename;
				}
				break;
			case "15mins":
				if (!in_array($filename, $this->_15mins)) {
					$this->_15mins[] = $filename;
				}
				break;
			case "30mins":
				if (!in_array($filename, $this->_30mins)) {
					$this->_30mins[] = $filename;
				}
				break;
			case "1hour":
				if (!in_array($filename, $this->_1hour)) {
					$this->_1hour[] = $filename;
				}
				break;
			case "24hrs":
				if (!in_array($filename, $this->_24hrs)) {
					$this->_24hrs[] = $filename;
				}
				break;
			case "connect":
				if (!in_array($filename, $this->_connect)) {
					$this->_connect[] = $filename;
				}
				break;
			case "setup":
				include $filename;
				break;
			default:
				echo "ERROR: invalid event type: '$type' for file: '$filename'\n";
		}
	}

/*===============================
** Name: unregevent
**  Disables an event
*/	function unregevent($type, $module, $filename) {
		if (Settings::get('debug') > 1) print("Deactivating Event:($type) File:($filename)\n");
		if (Settings::get('debug') > 2) sleep(1);
		
		$filename = $module . '/' . $filename;

		switch ($type) {
			case "towers":
				if (in_array($filename, $this->towers)) {
					$temp = array_flip($this->towers);
					unset($this->towers[$temp[$filename]]);
				}
				break;
			case "orgmsg":
				if (in_array($filename, $this->orgmsg)) {
					$temp = array_flip($this->orgmsg);
					unset($this->orgmsg[$temp[$filename]]);
				}
				break;
			case "msg":
				if (in_array($filename, $this->privMsgs)) {
					$temp = array_flip($this->privMsgs);
					unset($this->privMsgs[$temp[$filename]]);
				}
				break;
			case "priv":
				if (in_array($filename, $this->privChat)) {
					$temp = array_flip($this->privChat);
					unset($this->privChat[$temp[$filename]]);
				}
				break;
			case "extPriv":
				if (in_array($filename, $this->extPrivChat)) {
					$temp = array_flip($this->extPrivChat);
					unset($this->extPrivChat[$temp[$filename]]);
				}
				break;
			case "guild":
				if (in_array($filename, $this->guildChat)) {
					$temp = array_flip($this->guildChat);
					unset($this->guildChat[$temp[$filename]]);
				}
				break;
			case "joinPriv":
				if (in_array($filename, $this->joinPriv)) {
					$temp = array_flip($this->joinPriv);
					unset($this->joinPriv[$temp[$filename]]);
				}
				break;
			case "extJoinPriv":
				if (in_array($filename, $this->extJoinPriv)) {
					$temp = array_flip($this->extJoinPriv);
					unset($this->extJoinPriv[$temp[$filename]]);
				}
				break;
			case "leavePriv":
				if (in_array($filename, $this->leavePriv)) {
					$temp = array_flip($this->leavePriv);
					unset($this->leavePriv[$temp[$filename]]);
				}
				break;
			case "extLeavePriv":
				if (in_array($filename, $this->extLeavePriv)) {
					$temp = array_flip($this->extLeavePriv);
					unset($this->extLeavePriv[$temp[$filename]]);
				}
				break;
			case "extJoinPrivRequest":
				if (in_array($filename, $this->extJoinPrivRequest)) {
					$temp = array_flip($this->extJoinPrivRequest);
					unset($this->extJoinPrivRequest[$temp[$filename]]);
				}
				break;
			case "extKickPriv":
				if (in_array($filename, $this->extKickPriv)) {
					$temp = array_flip($this->extKickPriv);
					unset($this->extKickPriv[$temp[$filename]]);
				}
				break;
			case "logOn":
				if (in_array($filename, $this->logOn)) {
					$temp = array_flip($this->logOn);
					unset($this->logOn[$temp[$filename]]);
				}
				break;
			case "logOff":
				if (in_array($filename, $this->logOff)) {
					$temp = array_flip($this->logOff);
					unset($this->logOff[$temp[$filename]]);
				}
				break;
			case "2sec":
				if (in_array($filename, $this->_2sec)) {
					$temp = array_flip($this->_2sec);
					unset($this->_2sec[$temp[$filename]]);
				}
				break;
			case "1min":
				if (in_array($filename, $this->_1min)) {
					$temp = array_flip($this->_1min);
					unset($this->_1min[$temp[$filename]]);
				}
				break;
			case "10mins":
				if (in_array($filename, $this->_10mins)) {
					$temp = array_flip($this->_10mins);
					unset($this->_10mins[$temp[$filename]]);
				}
				break;
			case "15mins":
				if (in_array($filename, $this->_15mins)) {
					$temp = array_flip($this->_15mins);
					unset($this->_15mins[$temp[$filename]]);
				}
				break;
			case "30mins":
				if (in_array($filename, $this->_30mins)) {
					$temp = array_flip($this->_30mins);
					unset($this->_30mins[$temp[$filename]]);
				}
				break;
			case "1hour":
				if (in_array($filename, $this->_1hour)) {
					$temp = array_flip($this->_1hour);
					unset($this->_1hour[$temp[$filename]]);
				}
				break;
			case "24hrs":
				if (in_array($filename, $this->_24hrs)) {
					$temp = array_flip($this->_24hrs);
					unset($this->_24hrs[$temp[$filename]]);
				}
				break;
			case "connect":
				if (in_array($filename, $this->_connect)) {
					$temp = array_flip($this->_connect);
					unset($this->_connect[$temp[$filename]]);
				}
				break;
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
				$type = "joinPriv"; // Set message type.
				$sender	= $this->lookup_user($args[1]);// Get Name
				$char_id = $args[1];

				// Add sender to the chatlist.
				$this->chatlist[$sender] = true;
				
				// Echo
				if (Settings::get('echo') >= 1) newLine("Priv Group", $sender, "joined the channel.", Settings::get('echo'));

				// Remove sender if they are /ignored or /banned or They gone above spam filter
                if (Settings::get("Ignore"][$sender] == true || $this->banlist["$sender"]["name"] == "$sender" || $this->spam[$sender) > 100) {
					$this->privategroup_kick($sender);
					return;
				}
				// Check files, for all 'player joined channel events'.
				if ($this->joinPriv != NULL) {
					forEach ($this->joinPriv as $filename) {
						include $filename;
					}
				}
				
				// Kick if there access is restricted.
				if ($restricted === true) {
					$this->privategroup_kick($sender);
				}
			break;
			case AOCP_PRIVGRP_CLIPART: // 56, Incoming player left private chat
				$type = "leavePriv"; // Set message type.
				$sender	= $this->lookup_user($args[1]); // Get Name
				$char_id = $args[1];

				// Echo
				if (Settings::get('echo') >= 1) newLine("Priv Group", $sender, "left the channel.", Settings::get('echo'));

				// Remove from Chatlist array.
				unset($this->chatlist[$sender]);
				
				// Remove sender if they are /ignored or /banned or They gone above spam filter
				if (Settings::get("Ignore"][$sender] == true || $this->banlist["$sender"]["name"] == "$sender" || $this->spam[$sender) > 100) {
					return;
				}
				
				// Check files, for all 'player left channel events'.
				forEach ($this->leavePriv as $filename) {
					include $filename;
				}
			break;
			case AOCP_BUDDY_ADD: // 40, Incoming buddy logon or off
				// Basic packet data
				$sender	= $this->lookup_user($args[0]);
				$char_id = $args[0];
				$status	= 0 + $args[1];
				
				// store buddy info
				list($bid, $bonline, $btype) = $args;
				$this->buddyList[$bid]['uid'] = $bid;
				$this->buddyList[$bid]['name'] = $sender;
				$this->buddyList[$bid]['online'] = ($bonline ? 1 : 0);
				$this->buddyList[$bid]['known'] = (ord($btype) ? 1 : 0);

				//Ignore Logon/Logoff from other bots or phantom logon/offs
                if (Settings::get("Ignore"][$sender) == true || $sender == "") {
					return;
				}

				// If Status == 0(logoff) if Status == 1(logon)
				if ($status == 0) {
					$type = "logOff"; // Set message type
					
					// Echo
					//if (Settings::get('echo') >= 1) newLine("Buddy", $sender, "logged off", Settings::get('echo'));

					// Check files, for all 'player logged off events'
					if ($this->logOff != NULL) {
						forEach ($this->logOff as $filename) {
							$msg = "";
							include $filename;
						}
					}
				} else if ($status == 1) {
					$type = "logOn"; // Set Message Type
					
					// Echo
					if (Settings::get('echo') >= 1) newLine("Buddy", $sender, "logged on", Settings::get('echo'));

					// Check files, for all 'player logged on events'.
					if ($this->logOn != NULL) {
						forEach ($this->logOn as $filename) {
						  	$msg = "";
						  	include $filename;
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

				if (Settings::get("Ignore"][$sender] == true || $this->banlist["$sender"]["name"] == "$sender" || ($this->spam[$sender] > 100 && $this->vars['spam protection') == 1)) {
					$this->spam[$sender] += 20;
					return;
				}

				//Remove the prefix infront if there is one
				if ($message[0] == Settings::get("symbol") && strlen($message) > 1) {
					$message = substr($message, 1);
				}

				// Check privatejoin and tell Limits
				if (file_exists("./core/PRIV_TELL_LIMIT/check.php")) {
					include("./core/PRIV_TELL_LIMIT/check.php");
				}

				// Events
				if ($this->privMsgs != NULL) {
					forEach ($this->privMsgs as $file) {
						$msg = "";
						include $file;
					}
				}

				// Admin Code
				if ($restricted != true) {
					// Break down in to words.
					$words	= split(' ', strtolower($message));
					$access_level = $this->tellCmds[$words[0]]["access_level"];
					$filename = $this->tellCmds[$words[0]]["filename"];

				  	//Check if a subcommands for this exists
				  	if ($this->subcommands[$filename][$type]) {
					    if (preg_match("/^{$this->subcommands[$filename][$type]["cmd"]}$/i", $message)) {
							$access_level = $this->subcommands[$filename][$type]["access_level"];
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
				if ($this->banlist["$sender"]["name"] == $sender) {
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

					if ($this->privChat != NULL) {
						forEach ($this->privChat as $file) {
						  	$msg = "";
							include $file;
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
					
					if ($this->extPrivChat != NULL) {
						forEach ($this->extPrivChat as $file) {
						  	$msg = "";
							include $file;
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
	                if (Settings::get("Ignore"][$sender) == true)
						return;

					if ($this->banlist["$sender"]["name"] == "$sender")
						return;
				}

				if ($channel == "All Towers" || $channel == "Tower Battle Outcome") {
                    $type = "towers";
    				if ($this->towers != NULL) {
    					forEach ($this->towers as $file) {
    						$msg = "";
							include $file;
    					}
					}
                    return;
                } else if ($channel == "Org Msg") {
                    $type = "orgmsg";
    				if ($this->orgmsg != NULL) {
						foreach($this->orgmsg as $file) {
    						$msg = "";
							include $file;
    					}
					}
                    return;
                } else if ($channel == $this->vars["my guild"]) {
                    $type = "guild";
					$sendto = 'org';
					if ($this->guildChat != NULL)
    					foreach($this->guildChat as $file) {
							$msg = "";
							include $file;
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

				if ($this->extJoinPrivRequest != NULL) {
					forEach ($this->extJoinPrivRequest as $file) {
						$msg = "";
						include $file;
					}
				}
                return;
			break;
		}
	}

/*===============================
** Name: crons()
** Call php-Scripts at certin time intervals. 2 sec, 1 min, 15 min, 1 hour, 24 hours
*/	function crons() {
		global $db;
		switch($this->vars) {
			case $this->vars["2sec"] < time();
				$this->vars["2sec"] 	= time() + 2;
				forEach ($this->spam as $key => $value) {
					if ($value > 0) {
						$this->spam[$key] = $value - 10;
					} else {
						$this->spam[$key] = 0;
					}
				}
				if ($this->_2sec != NULL) {
					forEach ($this->_2sec as $filename) {
						include $filename;
					}
				}
				break;
			case $this->vars["1min"] < time();
				forEach ($this->largespam as $key => $value) {
					if ($value > 0) {
						$this->largespam[$key] = $value - 1;
					} else {
						$this->largespam[$key] = 0;
					}
				}
				$this->vars["1min"] 	= time() + 60;
				if ($this->_1min != NULL) {
					forEach ($this->_1min as $filename) {
						include $filename;
					}
				}
				break;
			case $this->vars["10mins"] < time();
				$this->vars["10mins"] 	= time() + (60 * 10);
				if ($this->_10mins != NULL) {
					forEach ($this->_10mins as $filename) {
						include $filename;
					}
				}
				break;
			case $this->vars["15mins"] < time();
				$this->vars["15mins"] 	= time() + (60 * 15);
				if ($this->_15mins != NULL) {
					forEach ($this->_15mins as $filename) {
						include $filename;
					}
				}
				break;
			case $this->vars["30mins"] < time();
				$this->vars["30mins"] 	= time() + (60 * 30);
				if ($this->_30mins != NULL) {
					forEach ($this->_30mins as $filename) {
						include $filename;
					}
				}
				break;
			case $this->vars["1hour"] < time();
				$this->vars["1hour"] 	= time() + (60 * 60);
				if ($this->_1hour != NULL) {
					forEach ($this->_1hour as $filename) {
						include $filename;
					}
				}
				break;
			case $this->vars["24hours"] < time();
				$this->vars["24hours"] 	= time() + ((60 * 60) * 24);
				if ($this->_24hrs != NULL) {
					forEach ($this->_24hrs as $filename) {
						include $filename;
					}
				}
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

	/*===============================
** Name: loadSQLFile
** Loads an sql file if there is an update
** Will load the sql file with name $namexx.xx.xx.xx.sql if xx.xx.xx.xx is greater
** than settings[$name . "_sql_version"]
*/	function loadSQLFile($module, $name, $forceUpdate = false) {
		global $db;
		$name = strtolower($name);
		
		// only letters, numbers, underscores are allowed
		if (!preg_match('/^[a-z0-9_]+$/', $name)) {
			echo "Invalid SQL file name: '$name' for module: '$module'!  Only numbers, letters, and underscores permitted!\n";
			return;
		}
		
		$settingName = $name . "_db_version";
		
		$core_dir = "./core/$module";
		$modules_dir = "./modules/$module";
		$dir = '';
		if ($d = dir($modules_dir)) {
			$dir = $modules_dir;
		} else if ($d = dir($core_dir)) {
			$dir = $core_dir;
		}
		
		$currentVersion = Settings::get($settingName);
		if ($currentVersion === false) {
			$currentVersion = 0;
		}

		$file = false;
		$maxFileVersion = 0;  // 0 indicates no version
		if ($d) {
			while (false !== ($entry = $d->read())) {
				if (is_file("$dir/$entry") && preg_match("/^" . $name . "([0-9.]*)\\.sql$/i", $entry, $arr)) {
					// if there is no version on the file, set the version to 0, and force update every time
					if ($arr[1] == '') {
						$file = $entry;
						$maxFileVersion = 0;
						$forceUpdate = true;
						break;
					}

					if (compareVersionNumbers($arr[1], $maxFileVersion) >= 0) {
						$maxFileVersion = $arr[1];
						$file = $entry;
					}
				}
			}
		}
		
		if ($file === false) {
			echo "No SQL file found with name '$name'!\n";
		} else if ($forceUpdate || compareVersionNumbers($maxFileVersion, $currentVersion) > 0) {
			// if the file had a version, tell them the start and end version
			// otherwise, just tell them we're updating the database
			if ($maxFileVersion != 0) {
				echo "Updating '$name' database from '$currentVersion' to '$maxFileVersion'...";
			} else {
				echo "Updating '$name' database...";
			}

			$fileArray = file("$dir/$file");
			//$db->beginTransaction();
			forEach ($fileArray as $num => $line) {
				$line = trim($line);
				// don't process comment lines or blank lines
				if ($line != '' && substr($line, 0, 1) != "#") {
					$db->exec($line);
				}
			}
			//$db->Commit();
			echo "Finished!\n";
		
			if (!Settings::save($settingName, $maxFileVersion)) {
				Settings::add($settingName, $module, 'noedit', $maxFileVersion);
			}
		} else {
			echo "Updating '$name' database...already up to date! version: '$currentVersion'\n";
		}
	}
}
?>