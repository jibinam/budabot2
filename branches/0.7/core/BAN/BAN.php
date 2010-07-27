<?php 
	$MODULE_NAME = "BAN";

	//Commands
	Command::register("", $MODULE_NAME, "ban_player.php", "ban", MODERATOR);
	Command::register("", $MODULE_NAME, "unban.php", "unban", MODERATOR);
	Command::register("", $MODULE_NAME, "banlist.php", "banlist", MODERATOR);

	//Events
	Event::register("1hour", $MODULE_NAME, "check_tempban.php");

	//Setup
	Event::register("setup", $MODULE_NAME, "upload_banlist.php");
	
	//Help Files
	Help::register("banhelp", $MODULE_NAME, "banhelp.txt", MODERATOR, "Ban a person from the bot.");
?>