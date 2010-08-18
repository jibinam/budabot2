<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Set Requirements for joining Privatechannel
   ** Version: 0.2
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 17.02.2006
   ** Date(last modified): 18.10.2006
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
if (preg_match("/^minlvl$/i", $message)) {
 	if (Settings::get("priv_req_lvl") == 0) {
 		$msg = "No Level Limit has been set for privategroup Invites.";
 	} else {
 		$msg = "Level Limit for responding on tells has been set to Lvl " . Settings::get("tell_req_lvl") . ".";
	}

    $this->send($msg, $sendto);
} else if (preg_match("/^minlvl ([0-9]+)$/i", $message, $arr)) {
	$minlvl = strtolower($arr[1]);
	
	if($minlvl > 220 || $minlvl < 0) {
		$msg = "<red>Minimum Level can be only set between 1-220<end>";
		$this->send($msg, $sender);
		return;
	}
	
	Settings::save("priv_req_lvl", $minlvl);
	
	if($minlvl == 0)
		$msg = "Player min level limit has been removed from privategroup Invites.";
	else
 		$msg = "Privategroup Invites are accepted from the level $minlvl and above.";

    $this->send($msg, $sendto);
} else if (preg_match("/^open$/i", $message)) {
 	if(Settings::get("oriv_req_open") == "all")
 		$msg = "No General Limit is set for privategroup Invites.";
 	elseif(Settings::get("priv_req_open") == "org")
 		$msg = "General Limit for privategroup Invites is set to Organisation members only.";
	else
		$msg = "General Limit for privategroup Invites is set to Bot members only.";
	
    $this->send($msg, $sendto);	
} else if (preg_match("/^open (org|all|members)$/i", $message, $arr)) {
	$open = ucfirst(strtolower($arr[1]));
	
	if($open == "all") {
		$msg = "General restrictions for privategroup invites has been removed.";
	} elseif($open == "org") {
		$msg = "Privategroup invites will be accepted only from Members of your Organisation";
	} else
		$msg = "Privategroup Invites will be accepted only from Members of this Bot";
	
	Settings::save("priv_req_open", $open);

    $this->send($msg, $sendto);
} else if (preg_match("/^faction/i", $message)) {
 	if(Settings::get("pirv_req_faction") == "all")
 		$msg = "No Faction Limit is set for privategroup Invites.";
	else
		$msg = "Faction Limit for privategroup Invits is set to " . Settings::get("priv_req_faction") . ".";
		
    $this->send($msg, $sendto); 	
} else if (preg_match("/^faction (omni|clan|neutral|all)$/i", $message, $arr)) {
	$faction = ucfirst(strtolower($arr[1]));
	Settings::save("priv_req_faction", $faction);
	
	if($faction == "all") {
		$msg = "Faction limit removed from privategroup invites.";
	} else {
		$msg = "Privategroup Invites are accepted only from the Faction $faction.";
	}
	
    $this->send($msg, $sendto);
} else if (preg_match("/^faction not (omni|clan|neutral)$/i", $message, $arr)) {
	$faction = "not ".ucfirst(strtolower($arr[1]));
	Settings::save("priv_req_faction", $faction);
	$msg = "Invites are limited to <highlight>$faction<end> only now.";

    $this->send($msg, $sendto);
} else if (preg_match("/^maxplayers$/i", $message)) {
 	if(Settings::get("priv_req_faction") == "all")
 		$msg = "No Faction Limit is set for privategroup Invites.";
	else
		$msg = "Faction Limit for privategroup Invits is set to " . Settings::get("priv_req_maxplayers") . ".";
	
    $this->send($msg, $sendto); 	 
} else if (preg_match("/^maxplayer ([0-9]+)$/i", $message, $arr)) {
	$maxplayers = strtolower($arr[1]);
	
	if ($maxplayers > 120) {
		$msg = "<red>Maximum allowed players can be set only to lower then 120<end>";
	    $this->send($msg, $sendto);
		return;
	}
	
	Settings::save("priv_req_maxplayers", $maxplayers);
	
	if ($maxplayers == 0) {
		$msg = "The Limit of the Amount of players in the privategroup has been removed.";
	} else {
		$msg = "The Limit of the Amount of players in the privategroup has been set to $maxplayers.";
	}
    $this->send($msg, $sendto);
} else {
	$syntax_error = true;
}
?>