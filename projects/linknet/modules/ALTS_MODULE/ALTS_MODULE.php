<?php
	$MODULE_NAME = "ALTS_MODULE";

	bot::event($MODULE_NAME, "setup", "setup.php");

    // Alternative Characters
	bot::command("msg", "$MODULE_NAME/alts.php", "alts", "all", "Alt Char handling");
	bot::command("msg", "$MODULE_NAME/alts.php", "altsadmin", "mod", "Alt Char handling");

	//Helpfile
	bot::help($MODULE_NAME, "alts", "alts.txt", "all", "How to set alts", "Basic Commands");
	bot::help($MODULE_NAME, "altsadmin", "altsadmin.txt", "all", "How to set alts (admins)", "Basic Commands");
?>