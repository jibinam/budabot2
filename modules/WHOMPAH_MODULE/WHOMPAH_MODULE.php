<?php
	require_once 'Whompah.class.php';
	
	$db->loadSQLFile($MODULE_NAME, "whompah_cities");
    
	$command->register($MODULE_NAME, "", "whompah.php", "whompah", "all", "Shows the whompah route from one city to another");
	CommandAlias::register($MODULE_NAME, 'whompah', 'whompahs');
	CommandAlias::register($MODULE_NAME, 'whompah', 'whompa');
	CommandAlias::register($MODULE_NAME, 'whompah', 'whompas');
	
	$help->register($MODULE_NAME, "whompah", "whompah.txt", "all", "How to find the whompah route from one city to another");
?>