<?php
	$MODULE_NAME = "CAP_XP_MODULE";

	//Max XP calculator
	Command::register($MODULE_NAME, "cap_xp.php", "capsk", ALL, "Max SK Calculator");
	Command::register($MODULE_NAME, "cap_xp.php", "capxp", ALL, "Max XP Calculator");

	//Help files
    Help::register($MODULE_NAME, "capxp.txt", "capxp", ALL, "Set your reasearch bar for max xp/sk");
?>
