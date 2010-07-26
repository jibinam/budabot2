<?php 
	$MODULE_NAME = "BAN";

	//Commands
	Command::register("msg", $MODULE_NAME, "ban_player.php", "ban", MODERATOR);
	Command::register("msg", $MODULE_NAME, "unban.php", "unban", MODERATOR);
	Command::register("msg", $MODULE_NAME, "banlist.php", "banlist", MODERATOR);
	Command::register("priv", $MODULE_NAME, "ban_player.php", "ban", MODERATOR);
	Command::register("priv", $MODULE_NAME, "unban.php", "unban", MODERATOR);
	Command::register("priv", $MODULE_NAME, "banlist.php", "banlist", MODERATOR);
	Command::register("guild", $MODULE_NAME, "ban_player.php", "ban", MODERATOR);
	Command::register("guild", $MODULE_NAME, "unban.php", "unban", MODERATOR);
	Command::register("guild", $MODULE_NAME, "banlist.php", "banlist", MODERATOR);

	//Events
	Event::register("1hour", $MODULE_NAME, "check_tempban.php");

	//Setup
	Event::register("setup", $MODULE_NAME, "upload_banlist.php");
	
	//Help Files
	Help::register("banhelp", $MODULE_NAME, "banhelp.txt", MODERATOR, "Ban a person from the bot.");
?>