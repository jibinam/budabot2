<?php
	$MODULE_NAME = "BANK_MODULE";

	// Bank browse
	Command::register($MODULE_NAME, "bankbrowse.php", "bank", ALL, "Browse the Org Bank.");
	
	// Backpack browse
	Command::register($MODULE_NAME, "backpackbrowse.php", "pack", ALL, "Browse an Org Bank backpack.");
	
	// Bank lookup
	Command::register($MODULE_NAME, "banklookup.php", "id", ALL, "Look up an item.");
	
	// Bank search
	Command::register($MODULE_NAME, "banksearch.php", "find", ALL, "Search the Org Bank for an item you need.");
	
	// Help
	Help::register($MODULE_NAME, "bank.txt", "bank", ALL, "How to search for an item.");

	// Thanks to Xyphos (RK1) for helping me bugfix
?>