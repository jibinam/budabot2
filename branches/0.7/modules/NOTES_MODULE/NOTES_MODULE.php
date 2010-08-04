<?php
	$MODULE_NAME = "NOTES_MODULE";
	
	//Setup
	DB::loadSQLFile($MODULE_NAME, "notes");

	//adds tower info to 'watch' list
	Command::register("", $MODULE_NAME, "notes.php", "note", GUILDMEMBER, "displays, adds, or removes a note from your list");
	Command::register("", $MODULE_NAME, "notes.php", "notes", GUILDMEMBER, "displays, adds, or removes a note from your list");

	//Help files
	Help::register("notes", $MODULE_NAME, "notes.txt", GUILDMEMBER, "Notes Help");
	
?>