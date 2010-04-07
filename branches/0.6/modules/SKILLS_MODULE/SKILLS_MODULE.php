<?
	$MODULE_NAME = "SKILLS_MODULE";
	$PLUGIN_VERSION = 1.0;

	//Skills module

	bot::command("", "$MODULE_NAME/aggdef.php", "aggdef", "all", "Agg/Def: Calculates weapon inits for your Agg/Def bar.");
	bot::command("", "$MODULE_NAME/as.php", "as", "all", "AS: Calculates Aimed Shot.");
	bot::command("", "$MODULE_NAME/nanoinit.php", "nanoinit", "all", "Nanoinit: Calculates Nano Init.");
	bot::command("", "$MODULE_NAME/fa.php", "fa", "all", "FA: Calculates Full Auto recharge.");
	bot::command("", "$MODULE_NAME/burst.php", "burst", "all", "Burst: Calculates Burst.");
	bot::command("", "$MODULE_NAME/fling.php", "fling", "all", "Fling: Calculates Fling.");
	bot::command("", "$MODULE_NAME/mafist.php", "mafist", "all", "MA Fist: Calculates your fist speed.");

	//Helpiles
	bot::help("skills", "$MODULE_NAME/skills.txt", "all", "Explains the various Skill commands", "Skills");

?>