<?php
	$MODULE_NAME = "RESEARCH_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, "research");
	
	Command::register($MODULE_NAME, "research.php", "research", ALL, "Info on Research");
	
	Help::register($MODULE_NAME, "research.txt", "Research", ALL, "Info on Research");

?>