<?php
	$MODULE_NAME = "FRIENDLIST_DIAG_MODULE";

	Command::register($MODULE_NAME, "friendlist.php", "friendlist", MODERATOR, "friendlist management");
	Command::register($MODULE_NAME, "rembuddy.php", "rembuddy", MODERATOR, "friendlist management");

?>