<?php 
	$MODULE_NAME = "CMD_MODULE";

	//Tell
	Command::register($MODULE_NAME, "cmd.php", "cmd", LEADER, "Creates a highly visible messaage");
	
	//Helpfile
	Help::register("cmd", $MODULE_NAME, "cmd.txt", ALL, "Repeating of a msg 3 times");
?>
