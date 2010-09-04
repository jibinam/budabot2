<?php
	require_once 'info_functions.php';

	$MODULE_NAME = "INFO_MODULE";

	Command::register($MODULE_NAME, "info.php", "info", ALL, "Shows basic info");
	
	// aliases
	Command::register($MODULE_NAME, "info.php", "breed", ALL, "Alias for !info breed");
	Command::register($MODULE_NAME, "info.php", "hd", ALL, "Alias for !info hd");
	Command::register($MODULE_NAME, "info.php", "lag", ALL, "Alias for !info lag");
	Command::register($MODULE_NAME, "info.php", "nd", ALL, "Alias for !info nd");
	Command::register($MODULE_NAME, "info.php", "stats", ALL, "Alias for !info stats");

?>