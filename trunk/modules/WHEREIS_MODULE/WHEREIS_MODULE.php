<?php
	$db->loadSQLFile($MODULE_NAME, "whereis");
	
	Command::register($MODULE_NAME, "", "whereis.php", "whereis", "all", "Whereis Database");
	
	Help::register($MODULE_NAME, "whereis", "whereis.txt", "all", "Whereis Database");
	
?>