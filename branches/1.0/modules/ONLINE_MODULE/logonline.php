<?php
/*
 ** Author: Mindrila (RK1)
 ** Description: Tells players logging on the actual online status
 ** Version: 0.1
 **
 ** Developed for: Budabot(http://budabot.com)
 **
 ** Date(created): 23.03.2010
 ** Date(last modified): 23.03.2010
 **
 ** Copyright (C) 2005, 2006, 2007 Carsten Lohmann
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

// include online_func.php for the actual working functions
require_once "online_func.php";

if (isset($this->guildmembers[$sender])) {
	$msg = "";
	$type = "msg";
	list($numonline, $msg, $list) = online($type, $sender, $sendto, $this);
	if ($numonline != 0) {
		$blob = bot::makeLink($msg, $list);
		bot::send($blob, $sendto);
	} else {
		bot::send($msg, $sendto);
	}
}
?>