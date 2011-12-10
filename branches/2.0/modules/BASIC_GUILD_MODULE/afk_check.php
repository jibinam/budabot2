<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: AFK Handling(checks afk status)
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 23.11.2005
   ** Date(last modified): 26.01.2007
   **
   ** Copyright (C) 2005, 2006, 2007 Carsten Lohmann
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

if(!preg_match("/^afk(.*)$/i", $message, $arr)) {
	$row = $db->query("SELECT afk FROM guild_chatlist_<myname> WHERE `name` = '$sender'", true);
	if($db->numrows() != 0) {
	    if($row->afk != '0') {
	        $db->execute("UPDATE guild_chatlist_<myname> SET `afk` = 0 WHERE `name` = '$sender'");
	        $msg = "<highlight>$sender<end> is back";
	        $chatBot->send($msg, "guild");
	    }
	}
	$name = split(" ", $message, 2);
	$name = $name[0];
	$name = ucfirst(strtolower($name));
    $uid = $chatBot->get_uid($name);
   	if ($uid) {
		$row = $db->query("SELECT afk FROM guild_chatlist_<myname> WHERE `name` = '$name'", true);
		if ($db->numrows() == 0 && Settings::get("guest_relay") == 1) {
			$row = $db->query("SELECT afk FROM priv_chatlist_<myname> WHERE `name` = '$name' AND `guest` = 1", true);
		}

		if ($db->numrows() != 0) {
			if ($row->afk == "1") {
				$msg = "<highlight>$name<end> is currently AFK.";
			} else if ($row->afk == "kiting") {
				$msg = "<highlight>$name<end> is currently Kiting.";
			} else if ($row->afk != "0") {
				$msg = "<highlight>$name<end> is currently AFK: <highlight>$row->afk<end>";
			}

			if ($msg != "") {
				$chatBot->send($msg, "guild");
			}
		}
	}
}
?>