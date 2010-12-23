<?php
   /*
   ** Author: Tyrence/Whiz (RK2)
   ** Description: Sends a message to each player about the city status when they logon, and reminds them to raise cloak when it is time
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 20-NOV-2007
   ** Date(last modified): 25-FEB-2010
   ** 
   ** Copyright (C) 2010 Jason Wheeler
   **
   ** Licence Infos: 
   ** This file is an addon to Budabot.
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
   
	$MODULE_NAME = "CITY_MODULE";
	
	bot::loadSQLFile($MODULE_NAME, 'org_city');

    bot::command("", "$MODULE_NAME/city_guild.php", "city", "guild", "Shows the status of the Citycloak");

	bot::event("guild", "$MODULE_NAME/city_guild.php", "city", "Records when the cloak is raised or lowered");
    bot::event("1min", "$MODULE_NAME/city_guild_timer.php", "city", "Checks timer to see if cloak can be raised or lowered");
	bot::event("1min", "$MODULE_NAME/city_guild_raise_cloak.php", "city", "Reminds the player who lowered cloak to raise it when it can be raised.");
	bot::event("logOn", "$MODULE_NAME/city_guild_logon.php", "city", "Displays summary of city status.");
	
	bot::addsetting($MODULE_NAME, "showcloakstatus", "Show cloak status to players at logon", "edit", "1", "Never;When cloak is down;Always", "0;1;2");
	
	// Helpfiles
	bot::help("citycloak", "$MODULE_NAME/citycloak.txt", "guild", "Status of the citycloak");
	
	// Auto Wave
	bot::command("guild","$MODULE_NAME/start.php", "startraid", "guild", "manually starts wave counter");
	bot::command("guild","$MODULE_NAME/stopraid.php", "stopraid", "guild", "manually stops wave counter");
	bot::event("guild", "$MODULE_NAME/start.php", "none", "Starts a wave counter when cloak is lowered");
	bot::event("2sec", "$MODULE_NAME/counter.php", "none", "Checks timer to see when next wave should come");
	
	// OS/AS timer
	bot::event("orgmsg", "$MODULE_NAME/os_timer.php", "none", "Sets a timer when an OS/AS is launched");
?>