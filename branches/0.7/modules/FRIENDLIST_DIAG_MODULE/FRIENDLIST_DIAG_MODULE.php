<?php
	$MODULE_NAME = "FRIENDLIST_DIAG_MODULE";

	// View backpacks or general searches.
	Command::register("", $MODULE_NAME, "friendlist.php", "friendlist", MODERATOR, "friendlist management");
	Command::register("", $MODULE_NAME, "rembuddy.php", "rembuddy", MODERATOR, "friendlist management");

?>