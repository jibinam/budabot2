<?php
	require_once('info_functions.php');

	$MODULE_NAME = "INFO_MODULE";

	Command::register("", $MODULE_NAME, "info.php", "info", ALL, "Shows basic info");

?>