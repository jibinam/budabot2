<?php
	$MODULE_NAME = "DYNA_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, "dyna");
	
	Command::register($MODULE_NAME, "dyna.php", "dyna", ALL, "Search for RK Dynaboss");
	
	Help::register($MODULE_NAME, "dyna.txt", "dyna", ALL, "Search for RK Dynaboss");
	
?>