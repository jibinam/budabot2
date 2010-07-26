<?php
	$MODULE_NAME = "LEPROCS_MODULE";
	$PLUGIN_VERSION = 1.0;

	//Search for Database Updates
	DB::loadSQLFile($MODULE_NAME, "leprocs");

    //nano Search
	Command::register("", $MODULE_NAME, "leprocs.php", "leprocs", ALL, "Searches for a nano and tells you were to get it.");
	Command::register("", $MODULE_NAME, "leprocs.php", "leproc", ALL, "Searches for a nano and tells you were to get it.");

?>