<?php
   /*
   ** Author: Sebuda/Derroylo (both RK2) + Linux compatibility Changes from Dak (RK2)
   ** Description: Creates the setup Procedure, Loads core classes and creates the bot mainloop.
   ** Version: 0.6
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 01.10.2005	
   ** Date(last modified): 12.01.2007
   ** 
   ** Copyright (C) 2005, 2006 Carsten Lohmann and J. Gracik
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

$version = "0.6.7";

echo "\n\n\n\n\n";
echo "		**************************************************\n";
echo "		****         Budabot Version: $version           ****\n";
echo "		****    written by Sebuda & Derroylo(RK2)     ****\n";
echo "		****                Project Site:             ****\n";
echo "		****    http://code.google.com/p/budabot2/    ****\n";
echo "		****               Support Forum:             ****\n";
echo "		****          http://www.budabot.com/         ****\n";
echo "		**************************************************\n";
echo "\n";

date_default_timezone_set("UTC");

if (isWindows()) {
    // Load Extention 
    dl("php_sockets.dll");
    dl("php_pdo_sqlite.dll");
    dl("php_pdo_mysql.dll");
} else {
    /*
    * Load Extentions, if not already loaded.
    *
    * Note: These are normally present in a
    * modern Linux system. This is a safeguard.
    */
    if (!extension_loaded('pdo_sqlite')) {
        @dl('pdo_sqlite.so');
    }
    if !extension_loaded('pdo_mysql')) {
        @dl('pdo_mysql.so');
    }
    
}

//Load Required Files
$config_file = $argv[1];
require_once $config_file;
require_once './core/Budabot.class.php';

//Set Error Level
error_reporting(E_ERROR | E_PARSE);
//error_reporting(-1);

//Show setup dialog
if (!file_exists("delete me for new setup")) {
	include "./core/SETUP/setup.php";
}

//Bring the ignore list to a bot readable format
$ignore = explode(";", $settings["Ignore"]);
unset($settings["Ignore"]);
forEach ($ignore as $bot){
	$bot = ucfirst(strtolower($bot));
	$settings["Ignore"][$bot] = true;
}
unset($ignore);


//Remove the account infos from the global var
$login = $vars['login'];
$password = $vars['password'];
unset($vars['login']);
unset($vars['password']);

//////////////////////////////////////////////////////////////
// Create new objects
global $db;
$db = new DB($settings["DB Type"], $settings["DB Name"], $settings["DB Host"], $settings["DB username"], $settings["DB password"]);
if ($db->errorCode != 0) {
	Logger::log(__FILE__, "Error in creating Database Object: $db->errorInfo", ERROR);
	sleep(5);
	die();
}

$chatBot = new Budabot($vars, $settings);

/////////////////////////////////////////////
// log on aoChat, msnChat                  //
$chatBot->connectAO($login, $password);//		
/////////////////////////////////////////////

//Clear the login and the password	
unset($login);
unset($password);

//Clear database settings
unset($settings["DB Type"]);
unset($settings["DB Name"]);
unset($settings["DB Host"]);
unset($settings["DB username"]);
unset($settings["DB password"]);

// Call Main Loop
main(true, $chatBot);
/*
** Name: main
** Main Loop
** Inputs: (bool)$forever
** Outputs: None
*/	function main($forever = true,&$chatBot) {
		$start = time();
		
		// Create infinite loop
		while ($forever==true) {					
			$chatBot->ping();
			Event::run_cron_jobs();
			if ($exec_connected_events == false && ((time() - $start) > 5))	{
				// TODO
			  	$chatBot->connectedEvents();
			  	$exec_connected_events = true;
			}
		}	
	}	
/*
** Name: callback
** Function called by Aochat each time a incoming packet is received.
** Inputs: (int)$type, (array)$arguments, (object)&$incBot
** Outputs: None
*/	function callback($type, $args) {
		global $chatBot;
		$chatBot->processCallback($type, $args);	
	}
	
/**
* isWindows is a little utility function to check
* whether the bot is running Windows or something
* else: returns true if under Windows, else false
*/	function isWindows() {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return true;
        } else {
            return false;
        }
    }
?>
