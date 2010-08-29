<?php
   /*
   ** Author: Sebuda, Derroylo (RK2)
   ** Description: Shows the adminlist of the bot
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

if (preg_match("/^adminlist$/i", $message)) {

	$list.= "<highlight>Administrators<end>\n";
	$admins = Admin::find_by_access_level(SUPERADMIN);
	forEach ($admins as $admin) {
		$admin_player = Player::create($admin->uid);
		$list.= "<tab>$admin_player->name (<orange>Super Administrator<end>) ";

		$is_online = $admin_player->is_online;
		if ($is_online === true) {
			$list .= "(<green>Online<end>)";
		} else if ($is_online === false) {
			$list .= "(<orange>Offline<end>)";
		} else {
			$list .= "(<red>Offline<end>)";
		}
		$list.= "\n";
	}
	forEach (Admin::find_by_access_level(ADMIN) as $admin) {
		$admin_player = Player::create($admin->uid);
		$list.= "<tab>$admin_player->name ";
		
		$is_online = $admin_player->is_online;
		if ($is_online === true) {
			$list .= "(<green>Online<end>)";
		} else if ($is_online === false) {
			$list .= "(<orange>Offline<end>)";
		} else {
			$list .= "(<red>Offline<end>)";
		}

		$list.= "\n";
	}

	$list.="<highlight>Moderators<end>\n";	
	forEach (Admin::find_by_access_level(MODERATOR) as $admin) {
		$admin_player = Player::create($admin->uid);
		$list.= "<tab>$admin_player->name ";
		
		$is_online = $admin_player->is_online;
		if ($is_online === true) {
			$list .= "(<green>Online<end>)";
		} else if ($is_online === false) {
			$list .= "(<orange>Offline<end>)";
		} else {
			$list .= "(<red>Offline<end>)";
		}

		$list.= "\n";
	}

	$list.=	"<highlight>Raidleaders<end>\n";	
	forEach (Admin::find_by_access_level(RAIDLEADER) as $admin) {
		$admin_player = Player::create($admin->uid);
		$list.= "<tab>$admin_player->name ";
		
		$is_online = $admin_player->is_online;
		if ($is_online === true) {
			$list .= "(<green>Online<end>)";
		} else if ($is_online === false) {
			$list .= "(<orange>Offline<end>)";
		} else {
			$list .= "(<red>Offline<end>)";
		}

		$list.= "\n";
	}
	
	//require './core/ADMIN/upload_admins.php';
	$link = Text::makeLink('Adminlist', $list);	
	$chatBot->send($link, $sendto);
} else {
	$syntax_error = true;
}
?>