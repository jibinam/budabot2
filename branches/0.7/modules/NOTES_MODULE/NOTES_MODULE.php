<?php
	$MODULE_NAME = "NOTES_MODULE";
	
	//Setup
	DB::loadSQLFile($MODULE_NAME, "notes");

	//adds tower info to 'watch' list
	Command::register("", $MODULE_NAME, "note.php", "note", GUILDMEMBER, "adds or removes a note from your list");
	Command::register("", $MODULE_NAME, "notes.php", "notes", GUILDMEMBER, "displays notes in your list");

	//Help files
	Help::register("Notes", $MODULE_NAME, "notes.txt", GUILDMEMBER, "Notes Help");
	
?>