<?php
	$MODULE_NAME = "NEWS_MODULE";

	//Setup
	bot::event($MODULE_NAME, "setup", "setup.php");

	//News
	bot::command("", "$MODULE_NAME/news.php", "news", "all", "Show News");
	bot::subcommand("", "$MODULE_NAME/news.php", "news (.+)", "guildadmin", "news", "Add News");
	bot::subcommand("", "$MODULE_NAME/news.php", "news del (.+)", "guildadmin", "news", "Delete a Newsentry");

	//Help files
	bot::help($MODULE_NAME, "news", "news.txt", "guild", "How to use news");
?>