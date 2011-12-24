<?php
   /*
   ** Author: Sebuda (RK2)
   ** Description: Removes a mod from the adminlist
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

if (preg_match("/^remmod (.+)$/i", $message, $arr)){
	$who = ucfirst(strtolower($arr[1]));
	
	if ($who == $sender) {
		$chatBot->send("<red>You can't kick yourself.<end>", $sendto);
		return;
	}

	if ($chatBot->admins[$who]["level"] != 3) {
		$chatBot->send("<red>Sorry $who is not a Moderator of this Bot.<end>", $sendto);
		return;
	}
	
	if ((int)$chatBot->admins[$sender]["level"] <= (int)$chatBot->admins[$who]["level"]){
		$chatBot->send("<red>You must have a rank higher then $who.", $sendto);
		return;
	}
	
	unset($chatBot->admins[$who]);
	$db->exec("DELETE FROM admin_<myname> WHERE `name` = '$who'");
	
	Buddylist::remove($who, 'admin');

	$chatBot->send("<highlight>$who<end> has been removed as Moderator of this Bot.", $sendto);
	$chatBot->send("Your moderator access to <myname> has been removed.", $who);
} else {
	$syntax_error = true;
}

?>