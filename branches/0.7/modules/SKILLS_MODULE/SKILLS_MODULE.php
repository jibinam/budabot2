<?php
	$MODULE_NAME = "SKILLS_MODULE";
	$PLUGIN_VERSION = 1.0;

	//Skills module

	Command::register("", $MODULE_NAME, "aggdef.php", "aggdef", ALL, "Agg/Def: Calculates weapon inits for your Agg/Def bar.");
	Command::register("", $MODULE_NAME, "as.php", "as", ALL, "AS: Calculates Aimed Shot.");
	Command::register("", $MODULE_NAME, "nanoinit.php", "nanoinit", ALL, "Nanoinit: Calculates Nano Init.");
	Command::register("", $MODULE_NAME, "fa.php", "fa", ALL, "FA: Calculates Full Auto recharge.");
	Command::register("", $MODULE_NAME, "burst.php", "burst", ALL, "Burst: Calculates Burst.");
	Command::register("", $MODULE_NAME, "fling.php", "fling", ALL, "Fling: Calculates Fling.");
	Command::register("", $MODULE_NAME, "mafist.php", "mafist", ALL, "MA Fist: Calculates your fist speed.");
	Command::register("", $MODULE_NAME, "dimach.php", "dimach", ALL, "Dimach: Calculates dimach facts.");
	Command::register("", $MODULE_NAME, "brawl.php", "brawl", ALL, "Brawl: Calculates brawl facts.");
	Command::register("", $MODULE_NAME, "fast.php", "fast", ALL, "Fast: Calculates Fast Attack recharge.");
	Command::register("", $MODULE_NAME, "fast.php", "fastattack", ALL, "alias for: fast");

	//Helpiles
	Help::register("skills", $MODULE_NAME, "skills.txt", ALL, "Explains the various Skill commands");

?>