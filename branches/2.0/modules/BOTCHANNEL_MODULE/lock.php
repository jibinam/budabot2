<?php
  /*
   ** Author: Derroylo (RK2)
   ** Description: Locking the private channel
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 10.03.2006
   ** Date(last modified): 31.01.2007
   ** 
   ** Copyright (C) 2006, 2007 Carsten Lohmann
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
   
if (preg_match("/^lock$/i", $message)) {
  	if (Settings::get("priv_status") == "closed") {
	    $chatBot->send("Private channel is already locked.", $sendto);
		return;
	}
	$msg = "The private channel has been locked by <highlight>$sender<end>.";
	$chatBot->send($msg, "priv");
	
	if ($type == "msg") {
		$chatBot->send("You have locked the private channel.", $sender);
	}
	
	Settings::save("priv_status", "closed");
} else if (preg_match("/^lock (.+)$/i", $message, $arr)) {
  	$reason = $arr[1];
	if (Settings::get("priv_status") == "closed") {
	    $chatBot->send("Private channel is already locked.", $sendto);
		return;
	}
	$msg = "The private channel has been locked by <highlight>$sender<end>. Reason: <highlight>$reason<end> ";
	$chatBot->send($msg);
	
	if ($type == "msg") {
		$chatBot->send("You have locked the private channel.", $sender);
	}
	
	Settings::save("priv_status", "closed");
	Settings::save("priv_status_reason", $reason);
} else if (preg_match("/^unlock$/i", $message)) {
  	if (Settings::get("priv_status") == "open") {
    	$chatBot->send("Private channel is already opened.", $sendto);
		return;
	}
	$msg = "The private channel has been opened by <highlight>$sender<end>.";
	$chatBot->send($msg);

	if ($type == "msg") {
		$chatBot->send("You have opened the private channel.", $sender);
	}
	
	Settings::save("priv_status", "open");
	Settings::save("priv_status_reason", "not set");
} else {
	$syntax_error = true;
}
?>