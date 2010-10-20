<?php
	$MODULE_NAME = "SERVER_STATUS_MODULE";

	//Server Status
	Command::register($MODULE_NAME, "server_status.php", "server", ALL, "Shows the Server status");	

	//Help files
    Help::register($MODULE_NAME, "serverstatus.txt", "serverstatus", ALL, "Show Serverstatus");
?>