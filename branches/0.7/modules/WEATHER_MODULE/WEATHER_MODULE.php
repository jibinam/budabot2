<?php
	$MODULE_NAME = "WEATHER_MODULE";

	Command::register($MODULE_NAME, "weather.php", "weather", ALL, "View Weather");

	Help::register("weather", $MODULE_NAME, "weather.txt", ALL, "Get weather info.");
?>