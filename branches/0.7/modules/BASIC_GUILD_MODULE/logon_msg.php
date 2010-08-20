<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Set logon messages from Guildmembers
   ** Version: 1.0
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

$db->query("SELECT `name`, `logon_msg` FROM org_members_<myname> WHERE `name` = '$sender'");
if ($db->numrows() == 0) {
    $msg = "You are not on the notify list of this bot.";
	$chatBot->send($msg, $sendto);
} else {
	$row = $db->fObject();
}

if (preg_match("/^logon$/i", $message)) {
	if ($row->logon_msg == 0 || $row->logon_msg == '') {
		$chatBot->send("Your logon message is currently blank.", $sendto);
	} else {
		$chatBot->send($row->logon_msg, $sendto);
	}
} else if (preg_match("/^logon clear$/i", $message)) {
    $db->query("UPDATE org_members_<myname> SET `logon_msg` = 0 WHERE `name` = '$sender'");
    $msg = "Logon message cleared.";
    $chatBot->send($msg, $sendto);
} else if (preg_match("/^logon (.+)$/i", $message, $arr)) {
	$arr[1] = str_replace("'", "''", $arr[1]);
	if (strlen($arr[1]) <= 200) {
		$db->query("UPDATE org_members_<myname> SET `logon_msg` = '$arr[1]' WHERE `name` = '$sender'");
		$msg = "Thank you $sender. Your logon message has been set.";
	} else {
		$msg = "Your logon message is too long. Please choose a shorter one.";
	}
    $chatBot->send($msg, $sendto);
}
?>
