<?php
	$MODULE_NAME = "RESEARCH_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, "research");
	
	Command::register($MODULE_NAME, "research.php", "research", ALL, "Info on Research");
	
	Help::register("Research", $MODULE_NAME, "research.txt", ALL, "Info on Research");

?>