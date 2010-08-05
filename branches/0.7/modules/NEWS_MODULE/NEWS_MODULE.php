<?php
	$MODULE_NAME = "NEWS_MODULE";
	
	//Setup
	Event::register("setup", $MODULE_NAME, "setup.php");

	//News
    Event::register("logOn", $MODULE_NAME, "news_logon.php", "none", "Sends a tell with news to players logging in");
	Command::register("", $MODULE_NAME, "news.php", "news", MEMBER, "Show News");
	Subcommand::register("", $MODULE_NAME, "news.php", "news (.+)", GUILDADMIN, "news", "Add News");
	Subcommand::register("", $MODULE_NAME, "news.php", "news del (.+)", GUILDADMIN, "news", "Delete a Newsentry");

	//Help files
	Help::register("news", $MODULE_NAME, "news.txt", MEMBER, "News");
?>