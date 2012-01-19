<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Adds a Administrator to the adminlist
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 30.01.2007
   ** Date(last modified): 30.01.2007
   **
   ** Copyright (C) 2007 C. Lohmann
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

if (preg_match("/^addadmin (.+)$/i", $message, $arr)){
	$who = ucfirst(strtolower($arr[1]));

	if ($chatBot->get_uid($who) == NULL){
		$chatBot->send("<red>Sorry the player you wish to add doesn't exist.<end>", $sendto);
		return;
	}
	
	if ($who == $sender) {
		$chatBot->send("<red>You can't add yourself to another group.<end>", $sendto);
		return;
	}

	if ($this->admins[$who]["level"] == 4) {
		$chatBot->send("<red>Sorry but $who is already a Administrator.<end>", $sendto);
		return;
	}
	
	if ($this->vars["SuperAdmin"] != $sender){
		$chatBot->send("<red>You need to be Super-Administrator to add a Administrator<end>", $sendto);
		return;
	}

	if (isset($this->admins[$who]["level"]) && $this->admins[$who]["level"] >= 2) {
		if ($this->admins[$who]["level"] > 4) {
			$chatBot->send("<highlight>$who<end> has been demoted to the rank of a Administrator.", $sendto);
			$chatBot->send("You have been demoted to the rank of a Administrator on {$this->vars["name"]}", $who);
		} else {
			$chatBot->send("<highlight>$who<end> has been promoted to the rank of a Administrator.", $sendto);
			$chatBot->send("You have been promoted to the rank of a Administrator on {$this->vars["name"]}", $who);
		}
		$db->exec("UPDATE admin_<myname> SET `adminlevel` = 4 WHERE `name` = '$who'");
		$this->admins[$who]["level"] = 4;
	} else {
		$db->exec("INSERT INTO admin_<myname> (`adminlevel`, `name`) VALUES (4, '$who')");
		$this->admins[$who]["level"] = 4;
		$chatBot->send("<highlight>$who<end> has been added to the Administratorgroup", $sendto);
		$chatBot->send("You got Administrator access to <myname>", $who);
	}

	Buddylist::add($who, 'admin');
} else {
	$syntax_error = true;
}
?>