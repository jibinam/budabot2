<?php
$MODULE_NAME = "CAP_XP_MODULE";
$PLUGIN_VERSION = 0.1;

	//Max XP calculator
	bot::command("guild", "$MODULE_NAME/cap_xp.php", "capsk", "all", "Max SK Calculator");
	bot::command("msg", "$MODULE_NAME/cap_xp.php", "capsk", "all", "Max SK Calculator");
	bot::command("priv", "$MODULE_NAME/cap_xp.php", "capsk", "all", "Max SK Calculator");
	
	bot::command("guild", "$MODULE_NAME/cap_xp.php", "capxp", "all", "Max XP Calculator");
	bot::command("msg", "$MODULE_NAME/cap_xp.php", "capxp", "all", "Max XP Calculator");
	bot::command("priv", "$MODULE_NAME/cap_xp.php", "capxp", "all", "Max XP Calculator");

	//Helpfiles
    bot::help("capxp", "$MODULE_NAME/max_experience.txt", "all", "Set your reasearch bar for max xp/sk", "Cap XP Module");
 
?>
