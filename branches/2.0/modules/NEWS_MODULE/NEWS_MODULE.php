<?php
	$MODULE_NAME = "NEWS_MODULE";

	//Setup
	Event::register("setup", $MODULE_NAME, "setup.php");

	//News
    Event::register("logOn", $MODULE_NAME, "news_logon.php", "Sends a tell with news to players logging in");
	Command::register($MODULE_NAME, "news.php", "news", MEMBER, "Show News");
	Command::register($MODULE_NAME, "news.php", "addnews", GUILDADMIN, "Add a News entry");
	Command::register($MODULE_NAME, "news.php", "delnews", GUILDADMIN, "Delete a News entry");
	
	//Set admin and user news
	Command::register($MODULE_NAME, "set_news.php", "privnews", RAIDLEADER, "Set news that are shown on privjoin");
	Command::register($MODULE_NAME, "set_news.php", "adminnews", MODERATOR, "Set adminnews that are shown on privjoin");
	Settings::add("news", $MODULE_NAME, "no", "hide", "Not set.");
	Settings::add("adminnews", $MODULE_NAME, "no", "hide", "Not set.");

	//Help files
	Help::register($MODULE_NAME, "news.txt", "news", MEMBER, "How to use news");
?>