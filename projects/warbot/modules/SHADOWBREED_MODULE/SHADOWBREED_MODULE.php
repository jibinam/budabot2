<?php
	$MODULE_NAME = "SHADOWBREED_MODULE";

	//Shadowbreed module
	bot::event($MODULE_NAME, "leavePriv", "left_chat.php", "shadowbreed");
	bot::event($MODULE_NAME, "joinPriv", "joined_chat.php", "shadowbreed");
	bot::event($MODULE_NAME, "2sec", "shadowbreed_check.php", "shadowbreed");
	
	bot::command("priv", "$MODULE_NAME/shadowbreed_order.php", "shadowbreed", "leader", "Show sb Order");
	bot::command("priv", "$MODULE_NAME/cast_shadowbreed.php", "s", "all", "Show ShadowbreedB Cast");
	bot::regGroup("shadowbreed", $MODULE_NAME, "Create a Shadowbreed List", "Shadowbreed", "s");
	
	bot::addsetting($MODULE_NAME, "shadowbreed_max", "Max Persons that are shown on shadowbreed list", "edit", "10", "10;15;20;25;30", '0', "mod", "$MODULE_NAME/shadowbreed_help.txt");

	//Help files
	bot::help($MODULE_NAME, "shadowbreed", "Shadowbreed.txt", "all", "Shadowbreed list", "Shadowbreed and Commands");
?>