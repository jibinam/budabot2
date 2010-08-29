<?php 
	require 'Banlist.class.php';

	$MODULE_NAME = "BAN";

	DB::loadSQLFile($MODULE_NAME, "banlist");

	//Commands
	Command::register($MODULE_NAME, "ban_player.php", "ban", MODERATOR, 'ban a player', 1);
	Command::register($MODULE_NAME, "unban.php", "unban", MODERATOR, 'unban a player', 1);
	Command::register($MODULE_NAME, "banlist.php", "banlist", MODERATOR, 'shows who is on the banlist', 1);

	//Events
	Event::register("1hour", $MODULE_NAME, "check_tempban.php", 'Check if temp bans are up yet', 1);
	
	//Help Files
	Help::register($MODULE_NAME, "banhelp.txt", "banhelp", MODERATOR, "Ban a person from the bot.");
?>