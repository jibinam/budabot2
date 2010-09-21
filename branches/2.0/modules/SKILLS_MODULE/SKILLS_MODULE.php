<?php
	require_once 'utils.php';

	$MODULE_NAME = "SKILLS_MODULE";

	//Skills module
	Command::register($MODULE_NAME, "aggdef.php", "aggdef", ALL, "Agg/Def: Calculates weapon inits for your Agg/Def bar.");
	Command::register($MODULE_NAME, "as.php", "as", ALL, "AS: Calculates Aimed Shot.");
	Command::register($MODULE_NAME, "nanoinit.php", "nanoinit", ALL, "Nanoinit: Calculates Nano Init.");
	Command::register($MODULE_NAME, "fa.php", "fa", ALL, "FA: Calculates Full Auto recharge.");
	Command::register($MODULE_NAME, "burst.php", "burst", ALL, "Burst: Calculates Burst.");
	Command::register($MODULE_NAME, "fling.php", "fling", ALL, "Fling: Calculates Fling.");
	Command::register($MODULE_NAME, "mafist.php", "mafist", ALL, "MA Fist: Calculates your fist speed.");
	Command::register($MODULE_NAME, "dimach.php", "dimach", ALL, "Dimach: Calculates dimach facts.");
	Command::register($MODULE_NAME, "brawl.php", "brawl", ALL, "Brawl: Calculates brawl facts.");
	Command::register($MODULE_NAME, "fast.php", "fast", ALL, "Fast: Calculates Fast Attack recharge.");
	Command::register($MODULE_NAME, "fast.php", "fastattack", ALL, "alias for: fast");
	
	//Xyphos' tools
	Command::register($MODULE_NAME, "inits.php", "inits", ALL, "shows how much inits you need for 1/1");
	Command::register($MODULE_NAME, "specials.php", "specials", ALL, "shows how much skill you need to cap specials recycle");

	//Helpiles
	Help::register($MODULE_NAME, "aggdef.txt", "aggdef", ALL, "How to use aggdef");
	Help::register($MODULE_NAME, "nanoinit.txt", "nanoinit", ALL, "How to use nanoinit");
	Help::register($MODULE_NAME, "as.txt", "as", ALL, "How to use as");
	Help::register($MODULE_NAME, "fa.txt", "fa", ALL, "How to use fa");
	Help::register($MODULE_NAME, "fling.txt", "fling", ALL, "How to use fling");
	Help::register($MODULE_NAME, "burst.txt", "burst", ALL, "How to use burst");
	Help::register($MODULE_NAME, "mafist.txt", "mafist", ALL, "How to use mafist");
	Help::register($MODULE_NAME, "brawl.txt", "brawl", ALL, "How to use brawl");
	Help::register($MODULE_NAME, "dimach.txt", "dimach", ALL, "How to use dimach");
	Help::register($MODULE_NAME, "fast.txt", "fast", ALL, "How to use fast");
?>