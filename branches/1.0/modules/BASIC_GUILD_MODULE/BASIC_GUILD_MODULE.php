<?php
	$MODULE_NAME = "BASIC_GUILD_MODULE";
	
	//Setup of the Basic Guild Modules
	bot::event("setup", "$MODULE_NAME/setup.php");
	
	// Afk Check
	bot::event("guild", "$MODULE_NAME/afk_check.php", "none", "Afk check");
	bot::command("guild", "$MODULE_NAME/afk.php", "afk", "all", "Sets a member afk");
	bot::command("guild", "$MODULE_NAME/kiting.php", "kiting", "all", "Sets a member afk kiting");
	
	//Tell and Tellall
	bot::command("guild msg", "$MODULE_NAME/tell.php", "tell", "leader", "Repeats an message 3 times in Orgchat");
	bot::command("guild msg", "$MODULE_NAME/tell.php", "tellall", "leader", "Sends a tell to all online guildmembers");
	
	//Helpfile
	bot::help("afk_kiting", "$MODULE_NAME/afk_kiting.txt", "guild", "Set yourself AFK/Kiting");
	bot::help("tell", "$MODULE_NAME/tell.txt", "guild", "How to use tell and tellall");
?>