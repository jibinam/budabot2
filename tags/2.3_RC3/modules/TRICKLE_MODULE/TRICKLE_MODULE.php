<?php
	require_once 'trickle_functions.php';

	DB::loadSQLFile($MODULE_NAME, "trickle");

	Command::register($MODULE_NAME, "", "trickle.php", "trickle", "all", "Shows how much skills you will gain by increasing an ability");

	// Help files
	Help::register($MODULE_NAME, "trickle", "trickle.txt", "all", "How to use trickle");
?>