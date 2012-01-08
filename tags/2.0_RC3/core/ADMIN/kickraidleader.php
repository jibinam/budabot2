<?php
   /*
   ** Author: Sebuda, Derroylo (RK2)
   ** Description: Removes a RL from the adminlist
   ** Version: 0.2
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 01.10.2005
   ** Date(last modified): 30.01.2007
   **
   ** Copyright (C) 2005,2006 J. Gracik, C. Lohmann
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

if (preg_match("/^kickraidleader (.+)$/i", $message, $arr)) {
	$who = ucfirst(strtolower($arr[1]));
	
	if ($who == $sender) {
		bot::send("<red>You can't kick yourself.<end>", $sendto);
		return;
	}

	if ($this->admins[$who]["level"] != 2) {
		bot::send("<red>Sorry $who is not a Raidleader of this Bot.<end>", $sendto);
		return;
	}
	
	if ((int)$this->admins[$sender]["level"] <= (int)$this->admins[$who]["level"]){
		bot::send("<red>You must have a rank higher then $who.", $sendto);
		return;
	}
	
	unset($this->admins[$who]);
	$db->exec("DELETE FROM admin_<myname> WHERE `name` = '$who'");
		
	$this->remove_buddy($who, 'admin');

	bot::send("<highlight>$who<end> has been removed as Raidleader of this Bot.", $sendto);
	bot::send("Your raidleader access to <myname> has been removed.", $who);
} else {
	$syntax_error = true;
}

?>