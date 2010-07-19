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
	if ($user_access_level == ADMIN) {
		$this->send("<red>Error! $who is already an administrator.<end>", $sendto);
		return;
	}
	
	$sender_access_level = AccessLevel::get_user_access_level($sender);
	if ($sender_access_level >= $user_access_level) {
		$this->send("<red>Error! You must have a higher access level than '$who' to modify his/her access.<end>", $sendto);
		return;
	}

	if ($user_access_level <= RAIDLEADER) {
		if ($user_access_level < ADMIN) {
			$this->send("<highlight>$who<end> has been demoted to the rank of Administrator.", $sendto);
			$this->send("You have been demoted to the rank of a Administrator on {$this->vars["name"]}", $who);
		} else {
			$this->send("<highlight>$who<end> has been promoted to the rank of Administrator.", $sendto);
			$this->send("You have been promoted to the rank of a Administrator on {$this->vars["name"]}", $who);
		}
		$db->query("UPDATE admin_<myname> SET `adminlevel` = ". ADMIN . " WHERE `uid` = $uid");
	} else {
		$db->query("INSERT INTO admin_<myname> (`adminlevel`, `uid`) VALUES (" . ADMIN . ", '$uid')");
		$this->send("<highlight>$who<end> has been added to the Administrator group", $sendto);
		$this->send("You got Administrator access to <myname>", $who);
	}

	$this->add_buddy($who, 'admin');
} else {
	$syntax_error = true;
}
?>