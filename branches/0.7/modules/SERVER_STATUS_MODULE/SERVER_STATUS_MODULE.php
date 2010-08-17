<?php
	$MODULE_NAME = "SERVER_STATUS_MODULE";
	
	Event::register("1min", $MODULE_NAME, "check_server_status.php", "Checks the status of the server and updates bot with new status if it's changed");
	Settings::add("server_status", $MODULE_NAME, "no", "hide", "up");

	//Server Status
	Command::register($MODULE_NAME, "server_status.php", "server", ALL, "Shows the Server status");	

	//Help files
    Help::register($MODULE_NAME, "serverstatus.txt", "serverstatus", ALL, "Show Serverstatus");
?>