<?php
   /*
   ** Author: Derroylo (RK2) (Updated by Blackruby RK2)
   ** Description: Shows level infos
   ** Version: 1.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 20.12.2005
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

if (preg_match("/^(level|lvl) ([0-9]+)$/i", $message, $arr)) {
	$db->query("SELECT * FROM levels WHERE level = $arr[2]");
	if (($row == $db->fObject()) !== FALSE)
        "<white>L $row->level: team {$row->teamMin}-{$row->teamMax}<end><highlight> | <end><red> PvP {$row->pvpMin}-{$row->pvpMax} <end><highlight> | <end><yellow>{$row->xpsk} XP/SK<end><highlight> | <end><orange>  Missions {$row->missions} <end><highlight> | <end><blue>{$row->tokens} token(s)<end>";
    } else{
        $msg = "The level must be between <highlight>1<end> and <highlight>220<end>";
    }
    // Send info back
    bot::send($msg, $sendto);
} else {
	$syntax_error = true;
}
?>