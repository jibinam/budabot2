<?php
	$MODULE_NAME = "NOTES_MODULE";
	
	//Setup
	DB::loadSQLFile($MODULE_NAME, "notes");

	Command::register($MODULE_NAME, "notes.php", "note", GUILDMEMBER, "displays, adds, or removes a note from your list");
	Command::register($MODULE_NAME, "notes.php", "notes", GUILDMEMBER, "displays, adds, or removes a note from your list");

	//Help files
	Help::register($MODULE_NAME, "notes.txt", "notes", GUILDMEMBER, "How to use notes");
	
?>