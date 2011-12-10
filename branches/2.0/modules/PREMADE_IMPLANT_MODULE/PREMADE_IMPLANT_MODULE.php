<?php
   /*
   ** Module: PREMADE_IMPLANT
   ** Author: Tyrence/Whiz (RK2)
   ** Description: Allows you search for the implants in the premade implant booths.
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): Fall 2008
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

	require_once('Implant.class.php');
	require_once('functions.php');

	$MODULE_NAME = "PREMADE_IMPLANT_MODULE";

	//Setup
	Event::register("setup", $MODULE_NAME, "setup.php");

	//adds tower info to 'watch' list
	Command::register($MODULE_NAME, "premade.php", "premade", ALL, "Searches for implants out of the premade implants booths");
	Command::register($MODULE_NAME, "premade_update.php", "premadeupdate", ALL, "Checks the premade imp db for updates");
	
	//Help files
	Help::register($MODULE_NAME, "premade_implant.txt", "premade", ALL, "Premade Implant Help");
	
?>