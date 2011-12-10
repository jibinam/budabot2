<?
   /*
   ** Author: Derroylo (RK2)
   ** Description: Guestchannel (manual join)
   ** Version: 0.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 23.01.2007
   ** Date(last modified): 25.01.2007
   ** 
   ** Copyright (C) 2007 Carsten Lohmann
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
   
if(eregi("^guestjoin$", $message)) {
 	$db->query("SELECT * FROM guests_<myname> WHERE `name` = '$sender'");
	$on_guest_list = $db->numrows();
	
	if($this->settings["guest_man_join"] == 1 && $on_guest_list == 1) {
		$this->vars["Guest"][$sender] = false;
		AOChat::privategroup_kick($sender);
		AOChat::privategroup_invite($sender);
	} elseif($this->settings["guest_man_join"] == 0) {
 	    $this->vars["Guest"][$sender] = false;
		AOChat::privategroup_kick($sender);
		AOChat::privategroup_invite($sender);
	} else 
		bot::send("You are not allowed to join the guestchannel, ask an Orgmember for an invite.", $sender);
} else 
	$syntax_error = true;
?>