<?php
	$MODULE_NAME = "WEATHER_MODULE";

	Command::register($MODULE_NAME, "weather.php", "weather", ALL, "View Weather");

	Help::register($MODULE_NAME, "weather.txt", "weather", ALL, "Get weather info.");
?>