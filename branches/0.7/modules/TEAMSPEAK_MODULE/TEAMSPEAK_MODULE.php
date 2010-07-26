<?php
	$MODULE_NAME = "TEAMSPEAK_MODULE";

	Command::register("", $MODULE_NAME, "teamspeak_server.php", "ts", GUILDMEMBER, "Show Status of the Teamspeak Server");
	
	Settings::add("ts_ip", $MODULE_NAME, "IP from the TS Server", "edit", "Not set yet.", "text", '0', MODERATOR);	
	Settings::add("ts_queryport", $MODULE_NAME, "Queryport for the TS Server", "edit", "51234", "number", '0', MODERATOR);
	Settings::add("ts_serverport", $MODULE_NAME, "Serverport for the TS Server", "edit", "8767", "number", '0', MODERATOR);
	Settings::add("ts_servername", $MODULE_NAME, "Name of the TS Server", "edit", "Not set yet.", "text", '0', MODERATOR);

	//Help files	
    Help::register("teamspeak", $MODULE_NAME, "ts.txt", GUILDMEMBER, "Using the Teamspeak plugin");
?>