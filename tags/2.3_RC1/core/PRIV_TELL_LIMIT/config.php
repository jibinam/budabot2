<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Set Requirements for joining Privatechannel and responding on tells
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 12.10.2006
   ** Date(last modified): 21.10.2006
   ** 
   ** Copyright (C) 2006 Carsten Lohmann
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
if (preg_match("/^limits$/i", $message)) {
	$list = "<header>::::: Limits on using the Bot :::::<end>\n\n";
	$list .= "The bot offers limits that apply to the private channel(like faction or level limit) and responding to tells. Click behind a setting on Change this to set it to a new value.\n\n";
	$list .= "<u>Responding to Tells</u>\n";
	$list .= "Faction: <highlight>";
	if (Setting::get("tell_req_faction") == "all") {
		$list .= "No Limit";
	} else {
		$list .= Setting::get("tell_req_faction");
	}
	$list .= "<end> (";
	$list .= Text::make_chatcmd("Change this", "/tell <myname> limits tell faction").")\n";
	$list .= "Level: <highlight>";
	if (Setting::get("tell_req_lvl") == 0) {
		$list .= "No Limit";
	} else {
		$list .= Setting::get("tell_req_lvl");
	}
	$list .= "<end> (";
	$list .= Text::make_chatcmd("Change this", "/tell <myname> limits tell minlvl").")\n";
	$list .= "General: <highlight>";
	if (Setting::get("tell_req_open") == "all") {
		$list .= "No general Limit";
	} else if (Setting::get("tell_req_open") == "org") {
		$list .= "Responding only to Players that are in the Organistion <myguild>";
	} else {
		$list .= "Responding only to players that are Members of this Bot";
	}
	$list .= "<end> (";
	$list .= Text::make_chatcmd("Change this", "/tell <myname> limits tell open").")\n";

	$list .= "\n<u>Privatgroup Invites</u>\n";
	$list .= "Faction: <highlight>" . Setting::get("priv_req_faction") . "<end> (";
	$list .= Text::make_chatcmd("Change this", "/tell <myname> limits priv faction").")\n";
	$list .= "Level: <highlight>";
	if (Setting::get("priv_req_lvl") == 0) {
		$list .= "No Limit";
	} else {
		$list .= Setting::get("priv_req_lvl");
	}
	$list .= "<end> (";
	$list .= Text::make_chatcmd("Change this", "/tell <myname> limits priv minlvl").")\n";
	$list .= "General: <highlight>";
	if (Setting::get("priv_req_open") == "all") {
		$list .= "No general Limit";
	} else if (Setting::get("priv_req_open") == "org") {
		$list .= "Accepting invites only from Members of the Organistion <myguild>";
	} else {
		$list .= "Accepting invites only from Members of this Bot";
	}
	$list .= "<end> (";
	
	$list .= Text::make_chatcmd("Change this", "/tell <myname> limits priv open").")\n";
	$list .= "Player Limit: <highlight>";
	if (Setting::get("priv_req_maxplayers") == 0) {
		$list .= "No Limit";
	} else {
		$list .= Setting::get("priv_req_maxplayers");
	}
	$list .= "<end> (";
	$list .= Text::make_chatcmd("Change this", "/tell <myname> limits priv maxplayers").")\n";

	$msg = Text::make_blob("Limits for privGroup and Tells", $list);
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^limits (priv|tell) faction$/i", $message, $arr)) {
 	$list .= "<header>::::: Faction Limit :::::<end>\n\n";
 	$list .= "Current Setting: <highlight>";
 	if ($arr[1] == "priv") {
 	 	if (Setting::get("priv_req_faction") == "all") {
			$list .= "No Limit";
		} else {
			$list .= Setting::get("priv_req_faction");
		}
 	} else {
 	 	if (Setting::get("tell_req_faction") == "all") {
			$list .= "No Limit";
		} else {
			$list .= Setting::get("tell_req_faction");
		}
	}
	$list .= "<end>\n\nChange it to:\n";
	$list .= Text::make_chatcmd("No Faction Limit", "/tell <myname> limits {$arr[1]} faction all")."\n\n";	
	$list .= Text::make_chatcmd("Omni only", "/tell <myname> limits {$arr[1]} faction omni")."\n";
	$list .= Text::make_chatcmd("Clan only", "/tell <myname> limits {$arr[1]} faction clan")."\n";	
	$list .= Text::make_chatcmd("Neutral only", "/tell <myname> limits {$arr[1]} faction neutral")."\n\n";
	$list .= Text::make_chatcmd("Not Clan", "/tell <myname> limits {$arr[1]} faction not clan")."\n";
	$list .= Text::make_chatcmd("Not Neutral", "/tell <myname> limits {$arr[1]} faction not neutral")."\n";
	$list .= Text::make_chatcmd("Not Omni", "/tell <myname> limits {$arr[1]} faction not omni")."\n";
	$msg = Text::make_blob("Faction Limit", $list);
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^limits (priv|tell) faction (omni|clan|neutral|all)$/i", $message, $arr)) {
	$faction = ucfirst(strtolower($arr[2]));
	$channel = strtolower($arr[1]);
	
	if ($channel == "priv") {
		Setting::save("priv_req_faction", $faction);
	} else {
		Setting::save("tell_req_faction", $faction);
	}
	
	if ($channel == "priv" && $faction == "all") {
		$msg = "Faction limit removed from private channel invites.";
	} else if ($channel == "priv") {
		$msg = "Private channel Invites are accepted only from the Faction $faction.";
	} else if ($channel == "tell" && $faction == "all") {
		$msg = "Faction limit removed for tell responces.";
	} else if ($channel == "tell") {
 		$msg = "Responding on tells will be done only for players with the Faction $faction.";
 	}
 	$chatBot->send($msg, $sendto);
} else if (preg_match("/^limits (priv|tell) faction not (omni|clan|neutral)$/i", $message, $arr)) {
	$faction = ucfirst(strtolower($arr[2]));
	$channel = strtolower($arr[1]);
	
	if ($channel == "priv") {
		Setting::save("priv_req_faction", "not ".$faction);
	} else {
		Setting::save("tell_req_faction", "not ".$faction);
	}
	
	if ($channel == "priv") {
		$msg = "Private channel invites are accepted only from player that are not $faction.";
	} else if ($channel == "tell") {
 		$msg = "Responding on tells will be done for players that are not $faction.";
 	}
 	$chatBot->send($msg, $sendto);
} else if (preg_match("/^limits (priv|tell) minlvl$/i", $message, $arr)) {
 	$list .= "<header>::::: Level Limit :::::<end>\n\n";
 	$list .= "Current Setting: <highlight>";
 	if ($arr[1] == "priv") {
  	 	if (Setting::get("priv_req_lvl") == 0) {
			$list .= "No Limit";
		} else {
			$list .= Setting::get("priv_req_lvl");
		}
 	} else {
  	 	if (Setting::get("tell_req_lvl") == 0) {
			$list .= "No Limit";
		} else {
			$list .= Setting::get("tell_req_lvl");
		}
	}
	$list .= "<end>\n\nChange it to:\n";
	$list .= Text::make_chatcmd("No Level limit", "/tell <myname> limits {$arr[1]} minlvl 0")."\n\n";	
	for ($i = 5; $i <= 220; $i += 5) {
		$list .= Text::make_chatcmd("Level limit $i", "/tell <myname> limits {$arr[1]} minlvl $i")."\n";
	}

	$msg = Text::make_blob("Level Limit", $list);
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^limits (priv|tell) minlvl ([0-9]+)$/i", $message, $arr)) {
	$minlvl = strtolower($arr[2]);
	$channel = strtolower($arr[1]);
	
	if ($minlvl > 220 || $minlvl < 0) {
		$msg = "<red>Minimum Level can be only set between 1-220<end>";
		$chatBot->send($msg, $sendto);
		return;
	}
	
	if ($channel == "priv") {
		Setting::save("priv_req_lvl", $minlvl);
	} else {
		Setting::save("tell_req_lvl", $minlvl);
	}
	
	if ($channel == "priv" && $minlvl == 0) {
		$msg = "Player min level limit has been removed from private channel invites.";
	} else if ($channel == "priv") {
		$msg = "Private channel Invites are accepted from the level $minlvl and above.";
	} else if ($channel == "tell" && $minlvl == 0) {
		$msg = "Player min level limit has been removed from responding on tells.";
	} else if ($channel == "tell") {
 		$msg = "Responding on tells will be done for the Minimumlevel of $minlvl.";
 	}
 	$chatBot->send($msg, $sendto);
} else if (preg_match("/^limits (priv|tell) open$/i", $message, $arr)) {
 	$list .= "<header>::::: General Limit :::::<end>\n\n";
 	$list .= "Current Setting: <highlight>";
 	if ($arr[1] == "priv") {
 	 	if (Setting::get("priv_req_open") == "all") {
			$list .= "No general Limit";
		} else if (Setting::get("priv_req_open") == "org") {
			$list .= "Responding only to Players that are in the Organistion <myguild>";
		} else {
			$list .= "Responding only to players that are Members of this Bot";
		}
 	} else {
		if (Setting::get("tell_req_open") == "all") {
			$list .= "No general Limit";
		} else if (Setting::get("tell_req_open") == "org") {
			$list .= "Responding only to Players that are in the Organistion <myguild>";
		} else {
			$list .= "Responding only to players that are Members of this Bot";
		}
	}
	$list .= "<end>\n\nChange it to:\n";
	$list .= Text::make_chatcmd("No General limit", "/tell <myname> limits {$arr[1]} open all")."\n\n";
	$list .= Text::make_chatcmd("Only for Members of your Organisation", "/tell <myname> limits {$arr[1]} open org")."\n";
	$list .= Text::make_chatcmd("Only for Members of the Bot", "/tell <myname> limits {$arr[1]} open members")."\n\n";

	$msg = Text::make_blob("General Limit", $list);
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^limits (priv|tell) open (all|org|members)$/i", $message, $arr)) {
	$open = strtolower($arr[2]);
	$channel = strtolower($arr[1]);
	
	if ($channel == "priv") {
		Setting::save("priv_req_open", $open);
	} else {
		Setting::save("tell_req_open", $open);
	}
	
	if ($channel == "priv" && $open == "all") {
		$msg = "General restrictions for private channel invites has been removed.";
	} else if ($channel == "priv" && $open == "org") {
		$msg = "Private channel invites will be accepted only from Members of your Organisation";
	} else if ($channel == "priv" && $open == "members") {
		$msg = "Private channel Invites will be accepted only from Members of this Bot";
	} else if ($channel == "tell" && $open == "all") {
		$msg = "General restriction for responding on tells has been removed.";
	} else if ($channel == "tell" && $open == "org") {
 		$msg = "Responding on tells will be done only for Members of your Organisation.";
 	} else if ($channel == "tell" && $open == "members") {
 		$msg = "Responding on tells will be done only for Members of this Bot.";
 	}
 	$chatBot->send($msg, $sendto);
} else if (preg_match("/^limits priv maxplayers$/i", $message, $arr)) {
 	$list .= "<header>::::: Limit of Players in the Bot :::::<end>\n\n";
 	$list .= "Current Setting: <highlight>";
	if (Setting::get("priv_req_maxplayers") == 0) {
		$list .= "No Limit";
	} else {
		$list .= Setting::get("priv_req_maxplayers");
	}

	$list .= "<end>\n\nChange it to:\n";
	$list .= Text::make_chatcmd("No Limit of Players", "/tell <myname> limits priv maxplayers 0")."\n\n";	
	for ($i = 6; $i <= 120; $i += 6) {
		$list .= Text::make_chatcmd("Set Maximum allowed Players in the Bot to $i", "/tell <myname> limits priv maxplayers $i")."\n";
	}

	$msg = Text::make_blob("Limit of Players in the Bot", $list);
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^limits priv maxplayers ([0-9]+)$/i", $message, $arr)) {
	$maxplayers = strtolower($arr[1]);
	
	if ($maxplayers > 120) {
		$msg = "<red>Maximum allowed players can be set only to lower then 120<end>";
		$chatBot->send($msg, $sendto);
		return;
	}
	
	Setting::save("priv_req_maxplayers", $maxplayers);
	
	if ($maxplayers == 0) {
		$msg = "The Limit of the Amount of players in the private channel has been removed.";
	} else {
		$msg = "The Limit of the Amount of players in the private channel has been set to $maxplayers.";
	} 
 	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>