<?php
	// Guess I can't use $chatBot->send in here, or else I get an error when logging in.

	Logger::log(__FILE__, "One momment, updating quote table now", INFO);
	
	$db->query("CREATE TEMPORARY TABLE quote_backup(`IDNumber` INTEGER NOT NULL PRIMARY KEY, `Who` VARCHAR(25), `OfWho` VARCHAR(25), `When` VARCHAR(25), `What` VARCHAR(1000))");
	$db->query("INSERT INTO quote_backup SELECT * FROM quote");
	$db->query("DROP TABLE quote");
	$db->query("CREATE TABLE quote (`IDNumber` INTEGER NOT NULL PRIMARY KEY, `Who` VARCHAR(25), `OfWho` VARCHAR(25), `When` VARCHAR(25), `What` VARCHAR(1000))");
	$db->query("INSERT INTO quote SELECT * FROM quote_backup");
	$db->query("DROP TABLE quote_backup");
	
	Logger::log(__FILE__, "Updating quote table is complete", INFO);
	
?>