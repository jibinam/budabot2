<?php
   /*
   ** Module: TOWER_WATCH
   ** Author: Tyrence/Whiz (RK2)
   ** Description: Allows you to keep track of the opentimes of tower sites.
   ** Version: 1.2
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 23-November-2007
   ** Date(last modified): 9-Mar-2010
   ** 
   ** Copyright (C) 2008 Jason Wheeler (bigwheels16@hotmail.com)
   **
   ** This module and all it's files and contents are licensed
   ** under the GNU General Public License.  You may distribute
   ** and modify this module and it's contents freely.
   **
   ** This module may be obtained at: http://www.box.net/shared/bgl3cx1c3z
   **
   */

	$MODULE_NAME = "TOWER_WATCH_MODULE";
	
	//Setup
	$this->loadSQLFile($MODULE_NAME, "tower_watch");

	//adds tower info to 'watch' list
	$this->command("", "$MODULE_NAME/scout.php", "scout", GUILDMEMBER, "adds tower info to watch list");
	
	// removes tower info from 'watch' list
	$this->command("", "$MODULE_NAME/remscout.php", "remscout", GUILDMEMBER, "removes tower info from watch list");
	
	//shows the open times for each tower site on the 'watch' list
	$this->command("", "$MODULE_NAME/opentimes.php", "opentimes", GUILDMEMBER, "shows status of towers");
	
	//Help files
	$this->help("Tower Watch", "$MODULE_NAME/tower_watch.txt", GUILDMEMBER, "Tower Watch Help");
	
	//Settings for this module	
	$this->addsetting("alarmpreview", "Sets how early alarm should sound for gas change in minutes.", "edit", 5, "number");
	
	$this->event("2sec", "$MODULE_NAME/show_gas_change.php", "scout", "Shows gas changes for tower sites on watch list in org chat");
	$this->event("logOn", "$MODULE_NAME/logon.php", "scout", "Displays summary of tower sites and gas levels on logon.");
	
?>