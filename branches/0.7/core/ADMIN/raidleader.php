<?php
   /*
   ** Author: Sebuda, Derroylo (RK2)
   ** Description: Adds a RL to the adminlist
   ** Version: 0.2
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 01.10.2005
   ** Date(last modified): 30.01.2007
   **
   ** Copyright (C) 2005, 2006, 2007 J. Gracik, C. Lohmann
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

if(preg_match("/^raidleader (.+)$/i", $message, $arr)){
	$who = ucfirst(strtolower($arr[1]));
	$uid = $this->get_uid($who);

	if ($uid == NULL){
		$this->send("<red>Error! Player '$who' does not exist.", $sendto);
		return;
	}
	
	if ($uid == $char_id) {
		$this->send("<red>Error! You can't kick yourself.<end>", $sendto);
		return;
	}

	$user_access_level = AccessLevel::get_user_access_level($who);
	if ($user_access_level == RAIDLEADER) {
		$this->send("<red>Error! $who is already a raid leader.<end>", $sendto);
		return;
	}
	
	$sender_access_level = AccessLevel::get_user_access_level($sender);
	if ($sender_access_level >= $user_access_level) {
		$this->send("<red>Error! You must have a higher access level than '$who' to modify his/her access.<end>", $sendto);
		return;
	}

	if ($user_access_level <= RAIDLEADER) {
		if ($user_access_level < RAIDLEADER) {
			$this->send("<highlight>$who<end> has been demoted to Raidleader.", $sendto);
			$this->send("You have been demoted to Raidleader on <myname>", $who);
		} else {
			$this->send("<highlight>$who<end> has been promoted to Raidleader.", $sendto);
			$this->send("You have been promoted to Raidleader on <myname>", $who);
		}
		$db->query("UPDATE admin_<myname> SET `adminlevel` = ". RAIDLEADER . " WHERE `uid` = $uid");
	} else {
		$db->query("INSERT INTO admin_<myname> (`adminlevel`, `uid`) VALUES (" . RAIDLEADER . ", '$uid')");
		$this->send("<highlight>$who<end> has been added as a Raidleader", $sendto);
		$this->send("You have been added as a Raidleader to <myname>", $who);
	}

	$this->add_buddy($who, 'admin');
} else {
	$syntax_error = true;
}
?>