<?php
	$MODULE_NAME = "SERVER_STATUS_MODULE";

	//Server Status
	bot::command("", "$MODULE_NAME/server.php", "server", "all", "Shows the Server status");	

	//Help files
    bot::help($MODULE_NAME, "server", "server.txt", "guild", "Show the server status");
?>