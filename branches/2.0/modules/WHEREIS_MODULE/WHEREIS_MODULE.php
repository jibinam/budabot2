<?php
	$MODULE_NAME = "WHEREIS_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, "whereis");
	
	Command::register($MODULE_NAME, "whereis.php", "whereis", ALL, "Whereis Database");
	
	Help::register($MODULE_NAME, "whereis.txt", "whereis", ALL, "Whereis Database");
	
?>