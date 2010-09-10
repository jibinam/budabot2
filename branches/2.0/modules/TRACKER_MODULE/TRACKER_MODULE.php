<?php
	$MODULE_NAME = "TRACKER_MODULE";
	
	DB::loadSQLFile($MODULE_NAME, 'tracked_users');
	DB::loadSQLFile($MODULE_NAME, 'tracking');
    
	Command::register($MODULE_NAME, "track.php", "track", MODERATOR, "Lists all users on tracking list");
	
	Event::register("logOn", $MODULE_NAME, "logOn.php", "Records when a tracked user logs on");
	Event::register("logOff", $MODULE_NAME, "logOff.php", "Records when a tracked user logs off");
?>