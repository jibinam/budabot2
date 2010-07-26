<?php
	$MODULE_NAME = "WHEREIS_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, "whereis");
	
	Command::register("", $MODULE_NAME, "whereis.php", "whereis", ALL, "Whereis Database");
	
	Help::register("whereis", $MODULE_NAME, "whereis.txt", ALL, "Whereis Database");
	
?>