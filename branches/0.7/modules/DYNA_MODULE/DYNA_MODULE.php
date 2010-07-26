<?php
	$MODULE_NAME = "DYNA_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, "dyna");
	
	Command::register("", $MODULE_NAME, "dyna.php", "dyna", ALL, "Search for RK Dynaboss");
	
	Help::register("dyna", $MODULE_NAME, "dyna.txt", ALL, "Search for RK Dynaboss");
	
?>