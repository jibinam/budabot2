<?php
/*
   ** Author: Plugsz (RK1)
   ** Description: Guides
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 12.21.2006
   ** Date(last modified): 12.21.2006
   ** 
   ** Copyright (C) 2006 Donald Vanatta
   **
   ** Licence Infos: 
   ** This file is for use with Budabot.
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
   
	require_once 'functions.php';
   
	$MODULE_NAME = "GUIDEBOT_MODULE";

	bot::command("", "$MODULE_NAME/guides.php", "GUIDES", "all", "Guides for AO");
	
	// aliases
	bot::command("", "$MODULE_NAME/guides.php", "breed", "all", "Alias for !guides breed");
	bot::command("", "$MODULE_NAME/guides.php", "healdelta", "all", "Alias for !guides healdelta");
	bot::command("", "$MODULE_NAME/guides.php", "lag", "all", "Alias for !guides lag");
	bot::command("", "$MODULE_NAME/guides.php", "nanodelta", "all", "Alias for !guides nanodelta");
	bot::command("", "$MODULE_NAME/guides.php", "stats", "all", "Alias for !guides stats");
	bot::command("", "$MODULE_NAME/guides.php", "buffs", "all", "Alias for !guides buffs");
?>