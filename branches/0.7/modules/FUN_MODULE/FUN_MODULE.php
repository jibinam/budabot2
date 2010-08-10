<?php
	$MODULE_NAME = "FUN_MODULE";

	// Ding
	Command::register($MODULE_NAME, "ding.php", "ding", ALL, "Shows a random ding gratz message.");

	// Doh
	Command::register($MODULE_NAME, "doh.php", "doh", ALL, "Shows a random doh message.");

	// Beer
	Command::register($MODULE_NAME, "beer.php", "beer", ALL, "Shows a random beer message.");

	// Cybor
	Command::register($MODULE_NAME, "cybor.php", "cybor", ALL, "Shows a random cybor message.");

	// Chuck
	Command::register($MODULE_NAME, "chuck.php", "chuck", ALL, "Shows a random Chuck Norris joke.");

	//Credz
	Command::register($MODULE_NAME, "credz.php", "credz", ALL, "Shows a random credits message.");

	//homer
	Command::register($MODULE_NAME, "homer.php", "homer", ALL, "Shows a random homer quote message.");

	//fight
	Command::register($MODULE_NAME, "fight.php", "fight", ALL, "Let two persons fight against each other.");

	//Help files
	Help::register("fun_module", $MODULE_NAME, "fun_module.txt", ALL, 'Fun commands', "Fun Module");
?>