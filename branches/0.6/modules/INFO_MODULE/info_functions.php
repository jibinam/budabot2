<?php
   /*
   ** Author: Tyrence (RK2)
   ** Description: Add tower sites to watch list
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 23.11.2007
   ** Date(last modified): 23.11.2007
   ** 
   ** Copyright (C) 2007 Jason Wheeler
   **
   ** Licence Infos: 
   ** This file is module for of Budabot.
   **
   ** This module is free software; you can redistribute it and/or modify
   ** it under the terms of the GNU General Public License as published by
   ** the Free Software Foundation; either version 2 of the License, or
   ** (at your option) any later version.
   **
   ** This module is distributed in the hope that it will be useful,
   ** but WITHOUT ANY WARRANTY; without even the implied warranty of
   ** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   ** GNU General Public License for more details.
   **
   ** You should have received a copy of the GNU General Public License
   ** along with this module; if not, write to the Free Software
   ** Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
   */
  
function getTopicContents($path, $fileName, $fileExt)
{
	// get the filename and read in the file
	$file = "$path$fileName$fileExt";
	$info = file_get_contents($file);
	
	// escape html characters
	//$info = str_replace("&", "&amp;", $info);
	//$info = str_replace("<", "&lt;", $info);
	//$info = str_replace(">", "&gt;", $info);
	$info = str_replace('"', '\\"', $info);
	
	return $info;
}
    
?>