<?php
   /*
   ** Author: Sebuda (RK2)
   ** Description: Removes a player from the banlist
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 01.10.2005
   ** Date(last modified): 10.12.2006
   **
   ** Copyright (C) 2005, 2006 J. Gracik
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

if (preg_match("/^unban (.+)$/i", $message, $arr)){
	$who = ucfirst(strtolower($arr[1]));
	
	if (!isset($this->banlist[$who])) {
		bot::send("<red>Sorry the player you wish to remove doesn't exist or isn't on the banlist.", $sendto);
		return;
	}
		
	unset($this->banlist[$who]);
	$db->exec("DELETE FROM banlist_<myname> WHERE name = '$who'");
	bot::send("You have revomed the ban for <highlight>$who<end>", $sendto);
} else if (preg_match("/^unbanorg (.+)$/i", $message, $arr)) {
	$who = ucwords(strtolower($arr[1]));
	
	if (!isset($this->banlist[$who])) {
		bot::send("<red>Sorry the org you wish to remove doesn�t exist or isn�t on the banlist.", $sender);
		return;		  
	}
		
	unset($this->banlist[$who]);
	$db->exec("DELETE FROM banlist_<myname> WHERE name = '$who'");	
	bot::send("You have revomed the ban for the members of <highlight>$who<end>", $sendto);
} else {
	$syntax_error = true;
}

?>