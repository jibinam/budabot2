<?php
	$MODULE_NAME = "SERVER_STATUS_MODULE";
	
	$this->regevent("1min", $MODULE_NAME, "check_server_status.php");
	Settings::add("server_status", $MODULE_NAME, "no", "hide", "up");

	//Server Status
	$this->command("", $MODULE_NAME, "server_status.php", "server", ALL, "Shows the Server status");	

	//Help files
    $this->help("serverstatus", $MODULE_NAME, "serverstatus.txt", ALL, "Show Serverstatus");
?>