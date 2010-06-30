<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Enable/Disable Command/events and sets Access Level for commands
   ** Version: 0.4
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 15.12.2005
   ** Date(last modified): 03.02.2007
   ** 
   ** Copyright (C) 2006, 2007 Carsten Lohmann
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

if (preg_match("/^config$/i", $message)) {
	$list = "<header>::::: Module Config :::::<end>\n\n";
	$list .= "Org Commands - " . 
		bot::makeLink('Enable All', '/tell <myname> config cmd enable guild', 'chatcmd') . " " . 
		bot::makeLink('Disable All', '/tell <myname> config cmd disable guild', 'chatcmd') . "\n";
	$list .= "Private Group Commands - " . 
		bot::makeLink('Enable All', '/tell <myname> config cmd enable priv', 'chatcmd') . " " . 
		bot::makeLink('Disable All', '/tell <myname> config cmd disable priv', 'chatcmd') . "\n";
	$list .= "Private Message Commands - " . 
		bot::makeLink('Enable All', '/tell <myname> config cmd enable msg', 'chatcmd') . " " . 
		bot::makeLink('Disable All', '/tell <myname> config cmd disable msg', 'chatcmd') . "\n";
	$list .= "ALL Commands - " . 
		bot::makeLink('Enable All', '/tell <myname> config cmd enable all', 'chatcmd') . " " . 
		bot::makeLink('Disable All', '/tell <myname> config cmd disable all', 'chatcmd') . "\n\n\n";
	
	$sql = "
		SELECT
			module,
			(SELECT COUNT(*) FROM cmdcfg_<myname> WHERE module = c.module AND status = 1) count_enabled,
			(SELECT COUNT(*) FROM cmdcfg_<myname> WHERE module = c.module AND status = 0) count_disabled
		FROM
			cmdcfg_<myname> c
		WHERE
			module <> 'none'
		GROUP BY
			module
		ORDER BY
			module ASC";

	$db->query($sql);
	$data = $db->fObject("all");
	forEach ($data as $row) {
		$db->query("SELECT * FROM hlpcfg_<myname> WHERE `module` = '".strtoupper($row->module)."'");
		$num = $db->numrows();
		if($num > 0)
			$b = "(<a href='chatcmd:///tell <myname> config help $row->module'>Helpfiles</a>)";
		else
			$b = "";
			
		if ($row->count_enabled > 0 && $row->count_disabled > 0) {
			$a = "(<yellow>Partial<end>)";
		} else if ($row->count_disabled == 0) {
			$a = "(<green>Running<end>)";
		} else {
			$a = "(<red>Disabled<end>)";
		}
			
		$c = "(<a href='chatcmd:///tell <myname> config $row->module'>Configure</a>)";
	
		$on = "<a href='chatcmd:///tell <myname> config mod $row->module enable all'>On</a>";
		$off = "<a href='chatcmd:///tell <myname> config mod $row->module disable all'>Off</a>";
		$list .= strtoupper($row->module)." $a ($on/$off) $c $b\n";
	}

	$msg = bot::makeLink("Module Config", $list);
	bot::send($msg, $sendto);
} else if (preg_match("/^config cmd (enable|disable) (all|guild|priv|msg)$/i", $message, $arr)) {
	$status = ($arr[1] == "enable" ? 1 : 0);
	$typeSql = ($arr[2] == "all" ? "`type` = 'guild' OR `type` = 'priv' OR `type` = 'msg'" : "`type` = '{$arr[2]}'");
	
	$sql = "SELECT type, file, cmd, admin FROM cmdcfg_<myname> WHERE (`cmdevent` = 'cmd' OR `cmdevent` = 'subcmd') AND ($typeSql)";
	$db->query($sql);
	$data = $db->fObject('all');
	forEach ($data as $row) {
	  	if ($status == 1) {
			bot::regcommand($row->type, $row->file, $row->cmd, $row->admin);
		} else {
			bot::unregcommand($row->type, $row->file, $row->cmd);
		}
	}
	
	$sql = "UPDATE cmdcfg_<myname> SET `status` = $status WHERE (`cmdevent` = 'cmd' OR `cmdevent` = 'subcmd') AND ($typeSql)";
	$db->exec($sql);
	
	bot::send("Commands(s) updated successfully.", $sendto);	
} else if (preg_match("/^config (mod|cmd|grp|event) (.+) (enable|disable) (priv|msg|guild|all)$/i", $message, $arr)) {
	if($arr[1] == "event") {
		$temp = explode(" ", $arr[2]);
	  	$cmdmod = $temp[0];
	  	$file = $temp[1];
	} else {
		$cmdmod = $arr[2];
		$type = $arr[4]; 	
	}
		
	if($arr[3] == "enable")
		$status = 1;
	else
		$status = 0;
	
	if($arr[1] == "mod" && $type == "all")
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `module` = '$cmdmod'");
	elseif($arr[1] == "mod" && $type != "all")
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `module` = '$cmdmod' AND `type` = '$type'");
	elseif($arr[1] == "cmd" && $type != "all")
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `cmd` = '$cmdmod' AND `type` = '$type' AND `cmdevent` = 'cmd'");
	elseif($arr[1] == "cmd" && $type == "all")
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `cmd` = '$cmdmod' AND `cmdevent` = 'cmd'");
	elseif($arr[1] == "grp" && $type != "all")
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `grp` = '$cmdmod' AND `type` = '$type' AND `cmdevent` = 'cmd'");
	elseif($arr[1] == "grp" && $type == "all")
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `grp` = '$cmdmod' AND `cmdevent` = 'cmd'");
	elseif($arr[1] == "event" && $file != "")
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `file` = '$file' AND `cmdevent` = 'event' AND `type` = '$cmdmod'");	
	else
		$msg = "Unknown Syntax for this command. Pls look into the help system for usage of this command.";

	if($db->numrows() == 0) {
		if($arr[1] == "mod" && $type == "all")
			$msg = "Could not find the Module <highlight>$cmdmod<end>";
		elseif($arr[1] == "mod" && $type != "all")
			$msg = "Could not find the Module <highlight>$cmdmod<end> for Channel <highlight>$type<end>";
		elseif($arr[1] == "cmd" && $type != "all")
			$msg = "Could not find the Command <highlight>$cmdmod<end> for Channel <highlight>$type<end>";
		elseif($arr[1] == "cmd" && $type == "all")
			$msg = "Could not find the Command <highlight>$cmdmod<end>";
		elseif($arr[1] == "grp" && $type != "all")
			$msg = "Could not find the Group <highlight>$cmdmod<end> for Channel <highlight>$type<end>";
		elseif($arr[1] == "grp" && $type == "all")
			$msg = "Could not find the Group <highlight>$cmdmod<end>";
		elseif($arr[1] == "event" && $file != "")
			$msg = "Could not find the Event <highlight>$cmdmod<end> for File <highlight>$file<end>";
		bot::send($msg, $sendto);
		return;
	}

	if($arr[1] == "mod" && $type == "all") {
		$msg = "Updated status of the module <highlight>$cmdmod<end> to <highlight>".$arr[3]."d<end>";
	} elseif($arr[1] == "mod" && $type != "all") {
		$msg = "Updated status of the module <highlight>$cmdmod<end> in Channel <highlight>$type<end> to <highlight>".$arr[3]."d<end>"; 
	} elseif($arr[1] == "cmd" && $type != "all") {
		$msg = "Updated status of command <highlight>$cmdmod<end> to <highlight>".$arr[3]."d<end> in Channel <highlight>$type<end>";
	} elseif($arr[1] == "cmd" && $type == "all") {
		$msg = "Updated status of command <highlight>$cmdmod<end> to <highlight>".$arr[3]."d<end>";
	} elseif($arr[1] == "grp" && $type != "all") {
		$msg = "Updated status of group <highlight>$cmdmod<end> to <highlight>".$arr[3]."d<end> in Channel <highlight>$type<end>";
	} elseif($arr[1] == "grp" && $type == "all") {
		$msg = "Updated status of group <highlight>$cmdmod<end> to <highlight>".$arr[3]."d<end>";
	} elseif($arr[1] == "event" && $type != "") {
		$msg = "Updated status of event <highlight>$cmdmod<end> to <highlight>".$arr[3]."d<end>";
	}

	bot::send($msg, $sendto);

	$data = $db->fObject("all");
	foreach($data as $row) {
	  	if($row->cmdevent != "event") {
		  	if($status == 1)
				bot::regcommand($row->type, $row->file, $row->cmd, $row->admin);
			else
				bot::unregcommand($row->type, $row->file, $row->cmd, $row->admin);
		} else {
		  	if($status == 1)
				bot::regevent($row->type, $row->file);
			else
				bot::unregevent($row->type, $row->file);
		}
	}

	if($arr[1] == "mod" && $type == "all") {
		$db->query("UPDATE cmdcfg_<myname> SET `status` = $status WHERE `module` = '$cmdmod' AND `cmdevent` = 'cmd'");
		$db->query("UPDATE cmdcfg_<myname> SET `status` = $status WHERE `module` = '$cmdmod' AND `cmdevent` = 'event'");
	} elseif($arr[1] == "mod" && $type != "all") {
		$db->query("UPDATE cmdcfg_<myname> SET `status` = $status WHERE `module` = '$cmdmod' AND `type` = '$type' AND `cmdevent` = 'cmd'");
		$db->query("UPDATE cmdcfg_<myname> SET `status` = $status WHERE `module` = '$cmdmod' AND `type` = '$type' AND `cmdevent` = 'event'");
	} elseif($arr[1] == "cmd" && $type != "all") {
		$db->query("UPDATE cmdcfg_<myname> SET `status` = $status WHERE `cmd` = '$cmdmod' AND `type` = '$type' AND `cmdevent` = 'cmd'");
	} elseif($arr[1] == "cmd" && $type == "all") {
		$db->query("UPDATE cmdcfg_<myname> SET `status` = $status WHERE `cmd` = '$cmdmod' AND `cmdevent` = 'cmd'");
	} elseif($arr[1] == "grp" && $type != "all") {
		$db->query("UPDATE cmdcfg_<myname> SET `status` = $status WHERE `grp` = '$cmdmod' AND `type` = '$type' AND `cmdevent` = 'cmd'");
	} elseif($arr[1] == "grp" && $type == "all") {
		$db->query("UPDATE cmdcfg_<myname> SET `status` = $status WHERE `grp` = '$cmdmod' AND `cmdevent` = 'cmd'");
	} elseif($arr[1] == "event" && $file != "") {
		$db->query("UPDATE cmdcfg_<myname> SET `status` = $status WHERE `type` = '$cmdmod' AND `cmdevent` = 'event' AND `file` = '$file'");
	}
} else if (preg_match("/^config (subcmd|cmd|grp) ([a-z0-9_]+) admin (msg|priv|guild|all) (\\d)$/i", $message, $arr)) {
	$channel = strtolower($arr[1]);
	$command = strtolower($arr[2]);
	$type = strtolower($arr[3]);
	$admin_level = $arr[4];

	if($channel == "cmd") {
		if($type == "all")
			$db->query("SELECT * FROM cmdcfg_<myname> WHERE `cmd` = '$command' AND `cmdevent` = 'cmd'");
		else
			$db->query("SELECT * FROM cmdcfg_<myname> WHERE `cmd` = '$command' AND `type` = '$type' AND `cmdevent` = 'cmd'");
	
		if($db->numrows() == 0) {
			if($type == "all")
				$msg = "Could not find the command <highlight>$command<end>";
			else
				$msg = "Could not find the command <highlight>$command<end> for Channel <highlight>$type<end>";
		  	bot::send($msg, $sendto);
		  	return;
		}
			
		switch($type) {
			case "all":
				if($this->tellCmds[$command])
					$this->tellCmds[$command]["admin level"] = $admin_level;
				if($this->privCmds[$command])
					$this->privCmds[$command]["admin level"] = $admin_level;
				if($this->guildCmds[$command])
					$this->guildCmds[$command]["admin level"] = $admin_level;
			break;
		  	case "msg":	
				$this->tellCmds[$command]["admin level"] = $admin_level;
		  	break;
		  	case "priv":
				$this->privCmds[$command]["admin level"] = $admin_level;
		  	break;
		  	case "guild":
				$this->guildCmds[$command]["admin level"] = $admin_level;
		  	break;
		}
		
		if($type == "all") {
			$db->query("UPDATE cmdcfg_<myname> SET `admin` = $admin_level WHERE `cmd` = '$command' AND `cmdevent` = 'cmd'");
			$msg = "Updated access of command <highlight>$command<end> to <highlight>$arr[4]<end>";
		} else {
			$db->query("UPDATE cmdcfg_<myname> SET `admin` = $admin_level WHERE `cmd` = '$command' AND `type` = '$type' AND `cmdevent` = 'cmd'");
			$msg = "Updated access of command <highlight>$command<end> in Channel <highlight>$type<end> to <highlight>$arr[4]<end>";
		}
	} elseif($channel == "grp") {
	  	if($type == "all")
			$db->query("SELECT * FROM cmdcfg_<myname> WHERE `grp` = '$command' AND `cmdevent` = 'cmd'");
		else
			$db->query("SELECT * FROM cmdcfg_<myname> WHERE `grp` = '$command' AND `type` = '$type' AND `cmdevent` = 'cmd'");
	
		if($db->numrows() == 0) {
			if($arr[3] == "all")
				$msg = "Could not find the group <highlight>$command<end>";
			else
				$msg = "Could not find the group <highlight>$command<end> for Channel <highlight>$type<end>";
		  	bot::send($msg, $sendto);
		  	return;
		}
		while($row = $db->fObject()) {
			switch($arr[3]) {
				case "all":
					if($this->tellCmds[$row->cmd])
						$this->tellCmds[$row->cmd]["admin level"] = $admin_level;
					if($this->privCmds[$row->cmd])
						$this->privCmds[$row->cmd]["admin level"] = $admin_level;
					if($this->guildCmds[$row->cmd])
						$this->guildCmds[$row->cmd]["admin level"] = $admin_level;
				break;
			  	case "msg":	
					$this->tellCmds[$row->cmd]["admin level"] = $admin_level;
			  	break;
			  	case "priv":
					$this->privCmds[$row->cmd]["admin level"] = $admin_level;
			  	break;
			  	case "guild":
					$this->guildCmds[$row->cmd]["admin level"] = $admin_level;
			  	break;
			}
		}
		
		if($arr[3] == "all") {
			$db->query("UPDATE cmdcfg_<myname> SET `admin` = $admin_level WHERE `grp` = '$command' AND `cmdevent` = 'cmd'");
			$msg = "Updated access of group <highlight>$command<end> to <highlight>$arr[4]<end>";
		} else {
			$db->query("UPDATE cmdcfg_<myname> SET `admin` = $admin_level WHERE `grp` = '$command' AND `type` = '$type' AND `cmdevent` = 'cmd'");
			$msg = "Updated access of group <highlight>$command<end> in Channel <highlight>$type<end> to <highlight>$arr[4]<end>";
		}
	} else {
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `type` = '$type' AND `cmdevent` = 'subcmd' AND `cmd` = '$command'");
		if($db->numrows() == 0) {
			$msg = "Could not find the subcmd <highlight>$command<end> for Channel <highlight>$type<end>";
		  	bot::send($msg, $sendto);
		  	return;
		}
		$row = $db->fObject();
		$this->subcommands[$row->file][$row->type]["admin"] = $admin_level;		
		$db->query("UPDATE cmdcfg_<myname> SET `admin` = $admin_level WHERE `type` = '$type' AND `cmdevent` = 'subcmd' AND `cmd` = '$command'");
		$msg = "Updated access of sub command <highlight>$command<end> in Channel <highlight>$type<end> to <highlight>$arr[4]<end>";
	}
	bot::send($msg, $sendto);
} else if (preg_match("/^config cmd ([a-z0-9_]+) (.+)$/i", $message, $arr)) {
	$cmd = strtolower($arr[1]);
	$module = strtoupper($arr[2]);
	$found_msg = 0;
	$found_priv = 0;
	$found_guild = 0;	

	$db->query("SELECT * FROM cmdcfg_<myname> WHERE `cmd` = '$cmd' AND `module` = '$module'");
	if($db->numrows() == 0)
		$msg = "Could not find the command <highligh>$cmd<end> in the module <highlight>$module<end>.";
	else {
		$list = "<header>::::: Configure command $cmd :::::<end>\n\n";
		$list .= "<u><highlight>Tells:<end></u>\n";	
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `cmd` = '$cmd' AND `type` = 'msg' AND `module` = '$module'");
		if($db->numrows() == 1) {
			$row = $db->fObject();

			$found_msg = 1;
			
			if($row->admin == 1 || $row->admin == "rl" || $row->admin == "raidleader")
				$row->admin = "Raidleader";
			elseif ($row->admin == 2 || $row->admin == "mod")
				$row->admin = "Moderator";
			elseif ($row->admin == 3 || $row->admin == "admin")
				$row->admin = "Administrator";
			else
				$row->admin = ucfirst(strtolower($row->admin));
		
			if($row->status == 1)
				$status = "<green>Enabled<end>";
			else
				$status = "<red>Disabled<end>";
			
			$list .= "Current Status: $status (Access: $row->admin) \n";
			$list .= "Enable or Disable Command: ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." enable msg'>ON</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." disable msg'>OFF</a>\n";

			$list .= "Set minimum access lvl to use this command:\n";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin msg " . ALL . "'>All</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin msg " . MEMBER . "'>Guest</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin msg " . GUILDMEMBER . "'>Guild</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin msg " . LEADER . "'>Leader</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin msg " . GUILDADMIN . "'>Guildadmin</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin msg " . RAIDLEADER . "'>RL</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin msg " . MODERATOR . "'>Mod</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin msg " . ADMIN . "'>Admin</a>\n";
		} else 
			$list .= "Current Status: <red>Unused<end>. \n";

		$list .= "\n\n<u><highlight>Private Channel:<end></u>\n";	
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `cmd` = '$cmd' AND `type` = 'priv' AND `module` = '$module'");
		if($db->numrows() == 1) {
			$row = $db->fObject();

			$found_priv = 1;

			if($row->admin == 1 || $row->admin == "rl" || $row->admin == "raidleader")
				$row->admin = "Raidleader";
			elseif ($row->admin == 2 || $row->admin == "mod")
				$row->admin = "Moderator";
			elseif ($row->admin == 3 || $row->admin == "admin")
				$row->admin = "Administrator";
			else
				$row->admin = ucfirst(strtolower($row->admin));

			if($row->status == 1)
				$status = "<green>Enabled<end>";
			else
				$status = "<red>Disabled<end>";

			$list .= "Current Status: $status (Access: $row->admin) \n";
			$list .= "Enable or Disable Command: ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." enable priv'>ON</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." disable priv'>OFF</a>\n";

			$list .= "Set minimum access lvl to use this command:\n";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin priv " . ALL . "'>All</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin priv " . MEMBER . "'>Guest</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin priv " . GUILDMEMBER . "'>Guild</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin priv " . LEADER . "'>Leader</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin priv " . GUILDADMIN . "'>Guildadmin</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin priv " . RAIDLEADER . "'>RL</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin priv " . MODERATOR . "'>Mod</a>  ";		
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin priv " . ADMIN . "'>Admin</a>\n";
		} else 
			$list .= "Current Status: <red>Unused<end>. \n";

		$list .= "\n\n<u><highlight>Guild Channel:<end></u>\n";
		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `cmd` = '$cmd' AND `type` = 'guild' AND `module` = '$module'");
		if($db->numrows() == 1) {
			$row = $db->fObject();
			
			$found_guild = 1;
			
			if($row->admin == 1 || $row->admin == "rl" || $row->admin == "raidleader")
				$row->admin = "Raidleader";
			elseif ($row->admin == 2 || $row->admin == "mod")
				$row->admin = "Moderator";
			elseif ($row->admin == 3 || $row->admin == "admin")
				$row->admin = "Administrator";
			else
				$row->admin = ucfirst(strtolower($row->admin));
				
			if($row->status == 1)
				$status = "<green>Enabled<end>";
			else
				$status = "<red>Disabled<end>";

			$list .= "Current Status: $status (Access: $row->admin) \n";
			$list .= "Enable or Disable Command: ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." enable guild'>ON</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." disable guild'>OFF</a>\n";

			$list .= "Set minimum access lvl to use this command:\n";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin guild " . ALL . "'>All</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin guild " . MEMBER . "'>Guest</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin guild " . GUILDMEMBER . "'>Guild</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin guild " . LEADER . "'>Leader</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin guild " . GUILDADMIN . "'>Guildadmin</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin guild " . RAIDLEADER . "'>RL</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin guild " . MODERATOR . "'>Mod</a>  ";
			$list .= "<a href='chatcmd:///tell <myname> config cmd ".$cmd." admin guild " . ADMIN . "'>Admin</a>  ";
		} else 
			$list .= "Current Status: <red>Unused<end>. \n";

		$db->query("SELECT * FROM cmdcfg_<myname> WHERE dependson = '$cmd' AND `type` = 'msg' AND `cmdevent` = 'subcmd' AND `module` = '$module'");
		if($db->numrows() != 0) {
			
			$list .= "\n\n<u><highlight>Available Subcommands in tells<end></u>\n";
			while($row = $db->fObject()) {
				if($row->description != "")
					$list .= "Description: $row->description\n";
				else
					$list .= "Command: $row->cmd\n";
					
				if($row->admin == 1 || $row->admin == "rl" || $row->admin == "raidleader")
					$row->admin = "Raidleader";
				elseif ($row->admin == 2 || $row->admin == "mod")
					$row->admin = "Moderator";
				elseif ($row->admin == 3 || $row->admin == "admin")
					$row->admin = "Administrator";
				else
					$row->admin = ucfirst(strtolower($row->admin));
				
				$list .= "Current Access: <highlight>$row->admin<end> \n";
				$list .= "Set min. access lvl to use this command: ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin msg " . ALL . "'>All</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin msg " . MEMBER . "'>Guest</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin msg " . GUILDMEMBER . "'>Guild</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin msg " . LEADER . "'>Leader</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin msg " . GUILDADMIN . "'>Guildadmin</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin msg " . RAIDLEADER . "'>RL</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin msg " . MODERATOR . "'>Mod</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin msg " . ADMIN . "'>Admin</a>\n\n";
			}
		}

		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `dependson` = '$cmd' AND `type` = 'priv' AND `cmdevent` = 'subcmd' AND `module` = '$module'");
		if($db->numrows() != 0) {
			$list .= "\n\n<u><highlight>Available Subcommands in Private Channel<end></u>\n";
			while($row = $db->fObject()) {
				if($row->description != "")
					$list .= "Description: $row->description\n";
				else
					$list .= "Command: $row->cmd\n";
					
				if($row->admin == 1 || $row->admin == "rl" || $row->admin == "raidleader")
					$row->admin = "Raidleader";
				elseif ($row->admin == 2 || $row->admin == "mod")
					$row->admin = "Moderator";
				elseif ($row->admin == 3 || $row->admin == "admin")
					$row->admin = "Administrator";
				else
					$row->admin = ucfirst(strtolower($row->admin));
				
				$list .= "Current Access: <highlight>$row->admin<end> \n";
				$list .= "Set min. access lvl to use this command: ";
				$list .= "<a href='chatcmd:///tell <myname> subcmd ".$row->cmd." admin priv " . ALL . "'>All</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> subcmd ".$row->cmd." admin priv " . MEMBER . "'>Guest</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> subcmd ".$row->cmd." admin priv " . GUILDMEMBER . "'>Guild</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> subcmd ".$row->cmd." admin priv " . LEADER . "'>Leader</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> subcmd ".$row->cmd." admin priv " . GUILDADMIN . "'>Guildadmin</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> subcmd ".$row->cmd." admin priv " . RAIDLEADER . "'>RL</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> subcmd ".$row->cmd." admin priv " . MODERATOR . "'>Mod</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> subcmd ".$row->cmd." admin priv " . ADMIN . "'>Admin</a>\n\n";
			}
		}

		$db->query("SELECT * FROM cmdcfg_<myname> WHERE `dependson` = '$cmd' AND `type` = 'guild' AND `cmdevent` = 'subcmd' AND `module` = '$module'");
		if($db->numrows() != 0) {
			$list .= "\n\n<u><highlight>Available Subcommands in Guild Channel<end></u>\n";
			while($row = $db->fObject()) {
				if($row->description != "")
					$list .= "Description: $row->description\n";
				else
					$list .= "Command: $row->cmd\n";
					
				if($row->admin == 1 || $row->admin == "rl" || $row->admin == "raidleader")
					$row->admin = "Raidleader";
				elseif ($row->admin == 2 || $row->admin == "mod")
					$row->admin = "Moderator";
				elseif ($row->admin == 3 || $row->admin == "admin")
					$row->admin = "Administrator";
				else
					$row->admin = ucfirst(strtolower($row->admin));
				
				$list .= "Current Access: <highlight>$row->admin<end> \n";
				$list .= "Set min. access lvl to use this command: ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin guild " . ALL . "'>All</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin guild " . MEMBER . "'>Guest</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin guild " . GUILDMEMBER . "'>Guildmembers</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin guild " . LEADER . "'>Leader</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin guild " . GUILDADMIN . "'>Guildadmin</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin guild " . RAIDLEADER . "'>RL</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin guild " . MODERATOR . "'>Mod</a>  ";
				$list .= "<a href='chatcmd:///tell <myname> config subcmd ".$row->cmd." admin guild " . ADMIN . "'>Admin</a>\n\n";
			}
		}		
		$msg = bot::makeLink(ucfirst($cmd)." config", $list);
	}
	bot::send($msg, $sendto);
} else if (preg_match("/^config grp (.+)$/i", $message, $arr)) {
	$grp = strtolower($arr[1]);

	$db->query("SELECT * FROM cmdcfg_<myname> WHERE `grp` = '$grp' AND `cmdevent` = 'cmd' ORDER BY `cmd`");
	if($db->numrows() == 0)
		$msg = "Could not find the group <highligh>$grp<end>";
	else {
		$list = "<header>::::: Configure group $grp :::::<end>\n\n";
		$list .= "<highlight><u>Commands of this group</u><end> \n";
		while($row = $db->fObject()) {
	  	  	if($oldcmd != $row->cmd) {
				$adv = "<a href='chatcmd:///tell <myname> config cmd $row->cmd $row->module'>Adv.</a>";
				if($row->description != "none")
				    $list .= "$row->description (Cmd: $row->cmd)($adv): $on  $off \n";
				else
				    $list .= "$row->cmd Cmd ($adv): $on  $off \n";
	            $oldcmd = $row->cmd;
	        }
		}
			
		$list .= "\n\n<u><highlight>Enable or disable group for seperate Channels</u><end> \n";	
		$list .= "Tells: <a href='chatcmd:///tell <myname> config grp $grp enable msg'>ON</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp $grp disable msg'>OFF</a>\n";
	
		$list .= "Private Channel: <a href='chatcmd:///tell <myname> config grp $grp enable priv'>ON</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp $grp disable priv'>OFF</a>\n";
		
		$list .= "Guild Channel: <a href='chatcmd:///tell <myname> config grp $grp enable guild'>ON</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp $grp disable guild'>OFF</a>\n";
		$list .= "\n\n";
		$list .= "<highlight><u>Set permissions for the group</u><end>\n";
		$list .= "Tells: ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin msg " . ALL . "'>All</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin msg " . MEMBER . "'>Guest</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin msg " . GUILDMEMBER . "'>Guild</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin msg " . LEADER . "'>Leader</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin msg " . GUILDADMIN . "'>Guildadmin</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin msg " . RAIDLEADER . "'>RL</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin msg " . MODERATOR . "'>Mod</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin msg " . ADMIN . "'>Admin</a>\n";
		
	
		$list .= "Private Channel: ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin priv " . ALL . "'>All</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin priv " . MEMBER . "'>Guest</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin priv " . GUILDMEMBER . "'>Guild</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin priv " . LDEADER . "'>Leader</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin priv " . GUILDADMIN . "'>Guildadmin</a>";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin priv " . RAIDLEADER . "'>RL</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin priv " . MODERATOR . "'>Mod</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin priv " . ADMIN . "'>Admin</a>\n";
	
		$list .= "Guild Channel: ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin guild " . ALL . "'>All</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin guild " . MEMBER . "'>Guest</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin guild " . GUILDMEMBER . "'>Guild</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin guild " . LEADER . "'>Leader</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin guild " . GUILDADMIN . "'>Guildadmin</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin guild " . RAIDLEADER . "'>RL</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin guild " . MODERATOR . "'>Mod</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config grp ".$grp." admin guild " . ADMIN . "'>Admin</a>\n";
		
		$msg = bot::makeLink(ucfirst($grp)." group config", $list);
	} 
	bot::send($msg, $sendto);
} else if (preg_match("/^config mod (.+)$/i", $message, $arr)) {
  	$mod = strtolower($arr[1]);
	$list = "<header>::::: Config for module $mod :::::<end>\n";
	$list .= "Here can you disable or enable Commandos/Events and also changing their Access Level\n";
	$list .= "The following options are available:\n";
	$list .= " - Click ON or Off behind the Modulename to Enable or Disable them completly.\n";
	$list .= " - Click ON or Off behind the Command/Eventname to Enable or Disable them.\n";
	$list .= " - Click Adv. behind the name to change their Status for the single Channels \n";
	$list .= "   and to change their Access Limit\n\n";

	$db->query("SELECT * FROM cmdcfg_<myname> WHERE `cmdevent` = 'cmd' AND `type` != 'setup' AND `dependson` = 'none' AND `module` = '$mod'"
        ." UNION ALL"
        ." SELECT * FROM cmdcfg_<myname> WHERE `cmdevent` = 'event' AND `type` != 'setup' AND `dependson` = 'none' AND `module` = '$mod'"
        ." ORDER BY `cmd`");
	$data = $db->fObject("all");
	foreach($data as $row) {
        if($row->cmdevent == "cmd" && $oldcmd != $row->cmd) {
			if($row->grp == "none") {
				$on = "<a href='chatcmd:///tell <myname> config cmd ".$row->cmd." enable all'>ON</a>";
				$off = "<a href='chatcmd:///tell <myname> config cmd ".$row->cmd." disable all'>OFF</a>";
				$adv = "<a href='chatcmd:///tell <myname> config cmd ".$row->cmd."'>Adv.</a>";
		
				if($row->description != "none")
				    $list .= "$row->description ($adv): $on  $off \n";
				else
				    $list .= "$row->cmd Command ($adv): $on  $off \n";
				$oldcmd = $row->cmd;
			} elseif($group[$row->grp] == false) {
				$on = "<a href='chatcmd:///tell <myname> config grp ".$row->grp." enable all'>ON</a>";
				$off = "<a href='chatcmd:///tell <myname> config grp ".$row->grp." disable all'>OFF</a>";
				$adv = "<a href='chatcmd:///tell <myname> config grp ".$row->grp."'>Adv.</a>";

				$db->query("SELECT * FROM cmdcfg_<myname> WHERE `module` = 'none' AND `cmdevent` = 'group' AND `type` = '$row->grp'");
				if($db->numrows() == 1) {
				  	$temp = $db->fObject();
					if($temp->description != "none")
				    	$list .= "$temp->description ($adv): $on  $off \n";
					else
				    	$list .= "$temp->grp group ($adv): $on  $off \n";				 	 	
				}		
				$group[$row->grp] = true;
			}
		} elseif ($row->cmdevent == "event") {
				$on = "<a href='chatcmd:///tell <myname> config event ".$row->type." ".$row->file." enable all'>ON</a>";
				$off = "<a href='chatcmd:///tell <myname> config event ".$row->type." ".$row->file." disable all'>OFF</a>";

				if($row->status == 1)
					$status = "<green>Enabled<end>";
				else
					$status = "<red>Disabled<end>";
		
				if($row->description != "none")
				    $list .= "$row->description($status): $on  $off \n";
				else
				    $list .= "$row->type Event($status): $on  $off \n";			  	
		}
	}

	$msg = bot::makeLink("Configuration for module $mod", $list);
	bot::send($msg, $sendto);
} else if (preg_match("/^config help (.+) admin (\\d)$/i", $message, $arr)) {
  	$help = strtolower($arr[1]);
	$admin_level = $arr[2];
	
	$db->query("SELECT * FROM hlpcfg_<myname> WHERE `name` = '$help' ORDER BY `name`");
	if($db->numrows() == 0) {
		bot::send("The helpfile <highlight>$help<end> doesn't exists!", $sendto);		  	
		return;
	}
	$row = $db->fObject();
	$db->query("UPDATE hlpcfg_<myname> SET `admin` = $admin_level WHERE `name` = '$help'");
	$this->helpfiles[$row->cat][$row->name]["admin level"] = $admin_level;
	bot::send("Updated access for helpfile <highlight>$help<end> to <highlight>".ucfirst(strtolower($arr[2]))."<end>.", $sendto);
} else if (preg_match("/^config help (.+)$/i", $message, $arr)) {
  	$mod = strtoupper($arr[1]);
	$list = "<header>::::: Configure helpfiles for module $mod :::::<end>\n\n";

	$db->query("SELECT * FROM hlpcfg_<myname> WHERE module = '$mod' ORDER BY name");
	$data = $db->fObject("all");
	foreach($data as $row) {
	  	$list .= "<highlight><u>Helpfile</u><end>: $row->name\n";
	  	$list .= "<highlight><u>Description</u><end>: $row->description\n";
	  	$list .= "<highlight><u>Category</u><end>: $row->cat\n";
	  	$list .= "<highlight><u>Set Permission</u><end>: ";
		$list .= "<a href='chatcmd:///tell <myname> config help $row->name admin " . ALL . "'>All</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config help $row->name admin " . MEMBER . "'>Guest</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config help $row->name admin " . GUILDMEMBER . "'>Guild</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config help $row->name admin " . LEADER . "'>Leader</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config help $row->name admin " . GUILDADMIN . "'>Guildadmin</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config help $row->name admin " . RAIDLEADER . "'>RL</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config help $row->name admin " . MODERATOR . "'>Mod</a>  ";
		$list .= "<a href='chatcmd:///tell <myname> config help $row->name admin " . ADMIN . "'>Admin</a>\n";
	  	$list .= "\n\n";
	}

	$msg = bot::makeLink("Configurate helpfiles for module $mod", $list);
	bot::send($msg, $sendto);
} else if (preg_match("/^config (.*)$/i", $message, $arr)) {
	$module = $arr[1];
	$list  = "<header>::::: Bot Settings :::::<end>\n\n";
 	$list .= "<highlight>You can see here a list of all Settings that can be changed without a restart of the bot. Please note that not all can be changed only the ones that have a 'Change this' behind their name, on the rest you can see only the current setting but can't change it. When you click on 'Change it' a new poopup cames up and you see a list of allowed options for this setting. \n\n<end>";
	$list .= "\n<highlight><u>" . strtoupper($module) . "</u><end>\n";
	

 	$db->query("SELECT * FROM settings_<myname> WHERE `mode` != 'hide' AND `module` = '$module'");
	if ($db->numrows() > 0) {
		$list .= "\n<i>Settings</i>\n";
	}
 	while ($row = $db->fObject()) {
		$cur = $row->mod;	
		
		if($row->help != "")
			$list .= "$row->description (<a href='chatcmd:///tell <myname> settings help $row->name'>Help</a>)";
		else
			$list .= $row->description;

		if($row->mode == "edit")
			$list .= " (<a href='chatcmd:///tell <myname> settings change $row->name'>Change this</a>)";
	
		$list .= ":  ";

		$options = explode(";", $row->options);
		if($options[0] == "color")
			$list .= $row->setting."Current Color</font>\n";
		elseif($row->intoptions != "0") {
			$intoptions = explode(";", $row->intoptions);
			$intoptions2 = array_flip($intoptions);
			$key = $intoptions2[$row->setting];
			$list .= "<highlight>{$options[$key]}<end>\n";
		} else
			$list .= "<highlight>$row->setting<end>\n";	
	}

	$sql = 
		"SELECT
			*,
			(SELECT count(*) FROM cmdcfg_<myname> WHERE cmd = c.cmd AND type = 'guild') guild_avail,
			(SELECT count(*) FROM cmdcfg_<myname> WHERE cmd = c.cmd AND type = 'guild' AND status = 1) guild_status,
			(SELECT count(*) FROM cmdcfg_<myname> WHERE cmd = c.cmd AND type ='priv') priv_avail,
			(SELECT count(*) FROM cmdcfg_<myname> WHERE cmd = c.cmd AND type = 'priv' AND status = 1) priv_status,
			(SELECT count(*) FROM cmdcfg_<myname> WHERE cmd = c.cmd AND type ='msg') msg_avail,
			(SELECT count(*) FROM cmdcfg_<myname> WHERE cmd = c.cmd AND type = 'msg' AND status = 1) msg_status
		FROM
			cmdcfg_<myname> c
		WHERE
			(`cmdevent` = 'cmd' OR `cmdevent` = 'subcmd')
			AND `module` = '$module'
		GROUP BY
			cmd";
	$db->query($sql);
	if ($db->numrows() > 0) {
		$list .= "\n<i>Commands</i>\n";
	}
	$data = $db->fObject("all");
	forEach ($data as $row) {
		$guild = '';
		$priv = '';
		$msg = '';

		$on = "<a href='chatcmd:///tell <myname> config cmd $row->cmd enable all'>ON</a>";
		$off = "<a href='chatcmd:///tell <myname> config cmd $row->cmd disable all'>OFF</a>";
		$adv = "<a href='chatcmd:///tell <myname> config cmd $row->cmd $row->module'>Adv.</a>";

		if ($row->msg_avail == 0)
			$tell = "|_";
		else if ($row->msg_status == 1)
			$tell = "|<green>T<end>";
		else
			$tell = "|<red>T<end>";
		
		if ($row->guild_avail == 0)
			$guild = "|_";
		else if ($row->guild_status == 1)
			$guild = "|<green>G<end>";
		else
			$guild = "|<red>G<end>";
		
		if ($row->priv_avail == 0)
			$priv = "|_";
		else if ($row->priv_status == 1)
			$priv = "|<green>P<end>";
		else
			$priv = "|<red>P<end>";

		if ($row->description != "") {
			$list .= "$row->cmd ($adv$tell$guild$priv): $on  $off - ($row->description)\n";
		} else {
			$list .= "$row->cmd - ($adv$tell$guild$priv): $on  $off\n";
		}
	}
	
	$db->query("SELECT * FROM cmdcfg_<myname> WHERE `cmdevent` = 'event' AND `type` != 'setup' AND `module` = '$module'");
	if ($db->numrows() > 0) {
		$list .= "\n<i>Events</i>\n";
	}
	while ($row = $db->fObject()) {
		$on = "<a href='chatcmd:///tell <myname> config event ".$row->type." ".$row->file." enable all'>ON</a>";
		$off = "<a href='chatcmd:///tell <myname> config event ".$row->type." ".$row->file." disable all'>OFF</a>";

		if($row->status == 1)
			$status = "<green>Enabled<end>";
		else
			$status = "<red>Disabled<end>";

		if($row->description != "none")
			$list .= "$row->type ($row->description) - ($status): $on  $off \n";
		else
			$list .= "$row->type - ($status): $on  $off \n";
	}

  	$msg = bot::makeLink("Bot Settings", $list);
 	bot::send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>