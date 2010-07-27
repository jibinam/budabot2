<?php
	$MODULE_NAME = "BASIC_CONNECTED_EVENTS";

	Event::register("connect", $MODULE_NAME, "systems_ready.php");
?>