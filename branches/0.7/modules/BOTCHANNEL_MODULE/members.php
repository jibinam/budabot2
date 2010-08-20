<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Guestchannel (invite/kick)
   ** Version: 1.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 23.11.2005
   ** Date(last modified): 21.11.2006
   ** 
   ** Copyright (C) 2005, 2006 Carsten Lohmann
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

if (preg_match("/^members$/i", $message)) {
	$db->query("SELECT * FROM members_<myname> ORDER BY `name`");
	$autoguests = $db->numrows();
	if ($autoguests != 0) {
	  	while ($row = $db->fObject()) {
	  	  	if (Buddylist::is_online($row->name)) {
				$status = "<green>Online";
				if ($chatBot->vars["Guest"][$row->name] == true) {
			    	$status .= " and in Guestchannel";
				}
			} else {
				$status = "<red>Offline";
			}

	  		$list .= "<tab>- $row->name ($status<end>)\n";
	  	}
	  	
	    $msg = Text::makeBlob("$autoguests players on the Autoinvitelist", $list);
	    if ($autoguests != 0) {
           	$chatBot->send($msg.$link, $sendto);
        } else {
           	$chatBot->send($msg, $sendto);
		}
	} else {
       	$chatBot->send("No player is on this list.", $sendto);
	}
} else {
	$syntax_error = true;
}

?>
