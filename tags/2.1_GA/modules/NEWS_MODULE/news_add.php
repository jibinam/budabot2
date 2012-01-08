<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Sets/shows/deletes News entries
   ** Version: 0.3
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 15.01.2006
   ** Date(last modified): 21.11.2006
   ** 
   ** Copyright (C) 2006 Carsten Lohmann
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

if (preg_match("/^news (.+)$/i", $message, $arr)) {
	$news = str_replace("'", "''", $arr[1]);
	$db->exec("INSERT INTO news (`time`, `name`, `news`) VALUES (".time().", '".$sender."', '$news')"); 
	$msg = "News has been added successfully.";

    $chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>