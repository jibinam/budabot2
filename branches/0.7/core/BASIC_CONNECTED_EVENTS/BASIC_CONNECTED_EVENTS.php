<?php
	$MODULE_NAME = "BASIC_CONNECTED_EVENTS";

	Event::register("connect", $MODULE_NAME, "systems_ready.php", 'Alert users bot is online and ready to be used', 1);
?>