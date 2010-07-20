<?php
	$MODULE_NAME = "RESEARCH_MODULE";
	
	$this->loadSQLFile($MODULE_NAME, "research");
	
	$this->command("", $MODULE_NAME, "research.php", "research", ALL, "Info on Research");
	
	Help::register("Research", $MODULE_NAME, "research.txt", ALL, "Info on Research");

?>