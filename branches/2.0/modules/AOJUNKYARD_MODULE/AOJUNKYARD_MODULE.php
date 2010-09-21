<?php

	$MODULE_NAME = "AOJUNKYARD_MODULE";

	Command::register($MODULE_NAME, "wtb.php", "wtb", ALL, "Brings up a listing of items that have been posting to shopping channel");
	
	Help::register($MODULE_NAME, "wtb.txt", "wtb", GUILDMEMBER, "How to use wtb");

?>