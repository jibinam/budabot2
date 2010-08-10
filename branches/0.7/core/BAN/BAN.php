<?php 
	$MODULE_NAME = "BAN";

	//Commands
	Command::register($MODULE_NAME, "ban_player.php", "ban", MODERATOR);
	Command::register($MODULE_NAME, "unban.php", "unban", MODERATOR);
	Command::register($MODULE_NAME, "banlist.php", "banlist", MODERATOR);

	//Events
	Event::register("1hour", $MODULE_NAME, "check_tempban.php", 'Check if temp bans are up yet', 1);

	//Setup
	Event::register("setup", $MODULE_NAME, "upload_banlist.php", '', 1);
	
	//Help Files
	Help::register("banhelp", $MODULE_NAME, "banhelp.txt", MODERATOR, "Ban a person from the bot.");
?>