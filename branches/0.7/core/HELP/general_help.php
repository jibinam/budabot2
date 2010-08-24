<?php
   /*
   ** Author: Sebuda (RK2)
   ** Description: General Help/Shows all help files
   ** Version: 0.3
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 01.10.2005
   ** Date(last modified): 21.11.2006
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

if (preg_match("/^about$/i", $message)) {
	global $version;
	$data = file_get_contents("./core/HELP/about.txt");
	$data = str_replace('<version>', $version, $data);
	$msg = Text::makeLink("About", $data);
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^help$/i", $message)) {
	global $version;
	$data .= "\nBudabot version: $version\n\n";
	$user_access_level = AccessLevel::get_user_access_level($sender);
	
	$sql = "SELECT name, module, description FROM hlpcfg_<myname> WHERE access_level >= $user_access_level ORDER BY module ASC";
	$data = $db->query($sql);
	$current_module = '';
	forEach ($data as $row) {
		if ($row->module != $current_module) {
			$list .= "\n<green>$row->module<end>\n";
			$current_module = $row->module;
		}
	
		if ($row->name != "") {
			$list .= "  *$row->name ($row->description) <a href='chatcmd:///tell <myname> help $row->name'>Click here</a>\n";
		} else {
			$list .= "  *$row->name <a href='chatcmd:///tell <myname> help $row->name'>Click here</a>\n";
		}
	}
	if ($list == "") {
		$msg = "<orange>No Help files found.<end>";
	} else {
		$msg = Text::makeLink("Help(main)", $data.$list);
	}
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^help (.+)$/i", $message, $arr)) {
	if (($output = Help::find($sender, $arr[1])) !== FALSE) {
		$chatBot->send($output, $sendto);
	} else {
		$chatBot->send("No help found on this topic.", $sendto);
	}
}

?>