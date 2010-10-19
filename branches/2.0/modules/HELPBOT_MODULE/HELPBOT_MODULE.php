<?php
	require_once 'Playfields.class.php';
	require_once 'db_utils.php';
	require_once 'trickle_functions.php';

	$MODULE_NAME = "HELPBOT_MODULE";
	
	Event::register("setup", $MODULE_NAME, "setup.php");
	
	DB::loadSQLFile($MODULE_NAME, "dyna");
	DB::loadSQLFile($MODULE_NAME, "research");
	DB::loadSQLFile($MODULE_NAME, "trickle");

	Command::register($MODULE_NAME, "kos_list.php", "kos", ALL, "Shows the Kill On Sight List");
	Command::register($MODULE_NAME, "time.php", "time", ALL, "Shows the time in the different timezones");
	Command::register($MODULE_NAME, "whois.php", "whois", ALL, "Char Infos (only current dim)");
	Command::register($MODULE_NAME, "whois.php", "whoisall", ALL, "Char Infos (all dim)");
	Command::register($MODULE_NAME, "whois.php", "whoisorg", ALL, "Org Infos");
	Command::register($MODULE_NAME, "biomat_identify.php", "bio", ALL, "Biomaterial Identify");
	Command::register($MODULE_NAME, "calc.php", "calc", ALL, "Calculator");
	Command::register($MODULE_NAME, "oe.php", "oe", ALL, "OE");
	Command::register($MODULE_NAME, "player_history.php", "history", ALL, "Show a history of a player");
	Command::register($MODULE_NAME, "smileys.php", "smileys", ALL, "The meaning of different Smileys");
	Command::register($MODULE_NAME, "inspect.php", "inspect", ALL, "Inspects Christmas/Eart Gifts and Peren. Containers");
	Command::register($MODULE_NAME, "aigen.php", "aigen", ALL, "Info about Alien City Generals(which VBs they drop)");
	Command::register($MODULE_NAME, "aiarmor.php", "aiarmor", ALL, "Tradeskillprocess for Alien Armor");
	Command::register($MODULE_NAME, "trickle.php", "trickle", ALL, "Shows how much skills you will gain by increasing an ability");
	Command::register($MODULE_NAME, "mobloot.php", "mobloot", ALL, "loot QL Infos ");
	Command::register($MODULE_NAME, "random.php", "random", ALL, "Random order");
	Command::register($MODULE_NAME, "cluster.php", "cluster", ALL, "cluster location");
	Command::register($MODULE_NAME, "buffitem.php", "buffitem", ALL, "buffitem look up");
	Command::register($MODULE_NAME, "whatbuffs.php", "whatbuffs", ALL, "find items that buff");
	Command::register($MODULE_NAME, "dyna.php", "dyna", ALL, "Search for RK Dynaboss");
	Command::register($MODULE_NAME, "research.php", "research", ALL, "Info on Research");
	Command::register($MODULE_NAME, "playfields.php", "playfields", ALL, "Shows all the playfields including IDs and short names");
	Command::register($MODULE_NAME, "waypoint.php", "waypoint", ALL, "Creats a waypoint link");

	// Flip or Roll command
	Command::register($MODULE_NAME, "roll.php", "flip", ALL, "Flip a coin");
	Command::register($MODULE_NAME, "roll.php", "roll", ALL, "Roll a random number");
	Command::register($MODULE_NAME, "roll.php", "verify", ALL, "Verifies a flip/roll");
	
	// Max XP calculator
	Command::register($MODULE_NAME, "cap_xp.php", "capsk", ALL, "Max SK Calculator");
	Command::register($MODULE_NAME, "cap_xp.php", "capxp", ALL, "Max XP Calculator");

	// Help files
	Help::register($MODULE_NAME, "whois.txt", "whois", ALL, "Show char stats at current and all dimensions");
    Help::register($MODULE_NAME, "biomat.txt", "biomat", ALL, "Identify an Biomaterial");
    Help::register($MODULE_NAME, "calculator.txt", "calculator", ALL, "Calculator");
    Help::register($MODULE_NAME, "oe.txt", "oe", ALL, "Calculating the OE ranges");
    Help::register($MODULE_NAME, "fliproll.txt", "fliproll", ALL, "How to use the flip and roll command");
    Help::register($MODULE_NAME, "history.txt", "history", ALL, "History of a player");
    Help::register($MODULE_NAME, "time.txt", "time", ALL, "Timezones");
    Help::register($MODULE_NAME, "kos_list.txt", "kos_list", ALL, "Kill On Sight List");
    Help::register($MODULE_NAME, "smiley_title_inspect.txt", "smiley_title_inspect", ALL, "Help for Smiley,Title Level and Inspect");
    Help::register($MODULE_NAME, "aiarmor.txt", "aiarmor", ALL, "Alien armor Tradeskillprocess");
	Help::register($MODULE_NAME, "alien_generals.txt", "alien_generals", ALL, "Alien City Generals Info");
	Help::register($MODULE_NAME, "buffitem.txt", "buffitem", ALL, "How to use buffitem");
	Help::register($MODULE_NAME, "cluster.txt", "cluster", ALL, "How to use cluster");
	Help::register($MODULE_NAME, "mobloot.txt", "mobloot", ALL, "How to use mobloot");
	Help::register($MODULE_NAME, "whatbuffs.txt", "whatbuffs", ALL, "How to use whatbuffs");
	Help::register($MODULE_NAME, "dyna.txt", "dyna", ALL, "Search for RK Dynaboss");
	Help::register($MODULE_NAME, "research.txt", "Research", ALL, "Info on Research");
	Help::register($MODULE_NAME, "capxp.txt", "capxp", ALL, "Set your reasearch bar for max xp/sk");
	Help::register($MODULE_NAME, "trickle.txt", "Trickle", ALL, "How to use trickle");
?>
