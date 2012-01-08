<?php
	Command::register($MODULE_NAME, "", "bank.php", "bank", "guild", "Browse the Org Bank");
	Command::register($MODULE_NAME, "", "updatebank.php", "updatebank", "admin", "Reloads the bank database from the AO Items Assistant file");
	
	Setting::add($MODULE_NAME, "bank_file_location", "Location of the AO Items Assistant csv dump file", "edit", "text", './modules/BANK_MODULE/import.csv', './modules/BANK_MODULE/import.csv', '', 'mod', 'updatebank');
	Setting::add($MODULE_NAME, 'max_bank_items', 'Number of items shown in search results', 'edit', "number", '200', '30;40;50;60');
	
	Help::register($MODULE_NAME, "bank", "bank.txt", "all", "How to search for an item on your bank(s)");
	Help::register($MODULE_NAME, "updatebank", "updatebank.txt", "admin", "How to update your bank database");
?>