<?php
	$MODULE_NAME = "SERVER_STATUS_MODULE";
	
	Event::register("1min", $MODULE_NAME, "check_server_status.php");
	Settings::add("server_status", $MODULE_NAME, "no", "hide", "up");

	//Server Status
	Command::register("", $MODULE_NAME, "server_status.php", "server", ALL, "Shows the Server status");	

	//Help files
    Help::register("serverstatus", $MODULE_NAME, "serverstatus.txt", ALL, "Show Serverstatus");
?>