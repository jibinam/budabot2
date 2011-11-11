<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Kicks a player from the privatechannel
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 17.02.2006
   ** Date(last modified): 18.02.2006
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

if (preg_match("/^kick (.+)$/i", $message, $arr)) {
    $name = ucfirst(strtolower($arr[1]));
	$charid = $chatBot->get_uid($name);
    if ($charid) {
        if ($chatBot->get_in_chatlist($charid) !== null) {
			$msg = "<highlight>$name<end> has been kicked from the private channel.";
		} else {
			$msg = "<highlight>$name<end> is not in the private channel.";
		}

		// we kick whether they are in the channel or not in case the channel list is bugged
		$chatBot->privategroup_kick($name);
    } else {
		$msg = "Player <highlight>{$name}<end> does not exist.";
	}
	
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}
?>