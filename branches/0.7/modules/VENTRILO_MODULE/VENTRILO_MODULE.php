<?php
	require_once "vent.inc.php";
	require_once "ventrilostatus.php";

	$MODULE_NAME = "VENTRILO_MODULE"; 

	Command::register("", $MODULE_NAME, "vent.php", "vent", GUILDMEMBER, "Ventrilo Server Info");
	
	Settings::add("ventaddress", $MODULE_NAME, "Ventrilo Server Address", "edit", "unknown", "text");
	Settings::add("ventport", $MODULE_NAME, "Ventrilo Server Port", "edit", "unknown", "text");
	Settings::add("ventpass", $MODULE_NAME, "Ventrilo Server Password", "edit", "unknown", "text");
	
	Settings::add("ventimplementation", $MODULE_NAME, "Platform your bot runs on", "edit", "1", "Windows;Linux", "1;2");
	Settings::add("showventpassword", $MODULE_NAME, "Show password with vent info?", "edit", "1", "true;false", "1;0");
	Settings::add("showextendedinfo", $MODULE_NAME, "Show extended vent server info?", "edit", "1", "true;false", "1;0");

?>