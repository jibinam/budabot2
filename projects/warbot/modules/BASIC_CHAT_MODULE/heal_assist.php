<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Creates a Doc Assist Macro
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 05.06.2006
   ** Date(last modified): 05.06.2006
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

global $heal_assist;
if (preg_match("/heal$/i", $message)) {
  	if (!isset($heal_assist)) {
		$msg = "No heal assist set atm.";
		$chatBot->send($msg, $sendto);
		return;
	}
} else if (preg_match("/^heal (.+)$/i", $message, $arr)) {
    $nameArray = explode(' ', $arr[1]);
    
	if (count($nameArray) == 1) {
		$name = ucfirst(strtolower($arr[1]));
		$uid = $chatBot->get_uid($name);
		if (!$uid) {
			$msg = "Player <highlight>$name<end> does not exist.";
			$chatBot->send($msg, $sendto);
			return;
		}
		
		$link = "<header>::::: Heal Assist Macro for $name :::::\n\n";
		$link .= "<a href='chatcmd:///macro HEAL /assist $name'>Click here to make an healassist $name macro</a>";
		$heal_assist = "DOCTORS: " . Text::make_blob("Heal Assist $name Macro", $link);
	} else {
		forEach ($nameArray as $key => $name) {
			$name = ucfirst(strtolower($name));
			$uid = $chatBot->get_uid($name);
			if (!$uid) {
				$msg = "Player <highlight>$name<end> does not exist.";
				$chatBot->send($msg, $sendto);
				return;
			}
			$nameArray[$key] = "/assist $name";
		}
		
		// reverse array so that the first player will be the primary assist, and so on
		$nameArray = array_reverse($nameArray);
		$heal_assist = 'DOCTORS: /macro HEAL ' . implode(" \\n ", $nameArray);
	}
} else {
	$syntax_error = true;
}
 
if ($heal_assist != '') {
	$chatBot->send($heal_assist, $sendto);
	
	// send message 2 more times (3 total) if used in private channel
	if ($type == "priv") {
		$chatBot->send($heal_assist, $sendto);
		$chatBot->send($heal_assist, $sendto);
	}
}
?>