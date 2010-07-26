<?php
	$MODULE_NAME = "HD_ND_MODULE";
	$PLUGIN_VERSION = 1.0;

	Command::register("", $MODULE_NAME, "hd.php", "hd", ALL, "shows heal delta table");
	Command::register("", $MODULE_NAME, "nd.php", "nd", ALL, "shows nano delta table");

?>