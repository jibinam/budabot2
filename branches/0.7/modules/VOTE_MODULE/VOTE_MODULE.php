<?php
	$MODULE_NAME = "VOTE_MODULE";
	
	$this->event("setup", $MODULE_NAME, "setup.php");

	$this->command("", $MODULE_NAME, "vote.php", "vote", ALL, "Vote/Polling");
	
	Settings::add("vote_channel_spam", $MODULE_NAME, "Showing Vote status messages in", "edit", "2", "PrivateGroup;Guild;PrivateGroup and Guild;Neither", "0;1;2;3", MODERATOR, "vote_settings.txt");
	Settings::add("vote_add_new_choices", $MODULE_NAME, "Can users add in there own choices?", "edit", "1", "No;Yes", "0;1", MODERATOR, "vote_settings.txt");
	Settings::add("vote_create_min", $MODULE_NAME, "Minimum org level needed to create votes.", "edit", "-1", "None;0;1;2;3;4;5;6", "-1;0;1;2;3;4;5;6", MODERATOR, "vote_settings.txt");
	Settings::add("vote_use_min", $MODULE_NAME, "Minimum org level needed to vote.", "edit", "-1", "None;0;1;2;3;4;5;6", "-1;0;1;2;3;4;5;6", MODERATOR, "vote_settings.txt");
	
	$this->event("2sec", $MODULE_NAME, "votes_check.php");
	
	Help::register("vote", $MODULE_NAME, "vote.txt", ALL, "Vote/Polling");
?>