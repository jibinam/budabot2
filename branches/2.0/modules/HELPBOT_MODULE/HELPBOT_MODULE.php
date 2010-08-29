<?php
	$MODULE_NAME = "HELPBOT_MODULE";

	//KOS-List Part
	Command::register($MODULE_NAME, "kos_list.php", "kos", ALL, "Shows the Kill On Sight List");

	//Time Part
	Command::register($MODULE_NAME, "time.php", "time", ALL, "Shows the time in the different timezones");

    //Whois Part
	Command::register($MODULE_NAME, "whois.php", "whois", ALL, "Char Infos (only current dim)");

    //Whoisall Part
	Command::register($MODULE_NAME, "whois.php", "whoisall", ALL, "Char Infos (all dim)");

	//Whoisorg Part
	Command::register($MODULE_NAME, "whois.php", "whoisorg", ALL, "Org Infos");

	//Biomaterial identification
	Command::register($MODULE_NAME, "biomat_identify.php", "bio", ALL, "Biomaterial Identify");
	
	//Calculator
	Command::register($MODULE_NAME, "calc.php", "calc", ALL, "Calculator");

	//OE Calculator
	Command::register($MODULE_NAME, "oe.php", "oe", ALL, "OE");

	//Flip or Roll command
	Command::register($MODULE_NAME, "roll.php", "flip", ALL, "Flip a coin");
	Command::register($MODULE_NAME, "roll.php", "roll", ALL, "Roll a random number");
	Command::register($MODULE_NAME, "roll.php", "verify", ALL, "Verifies a flip/roll");

	//Player History
	Command::register($MODULE_NAME, "player_history.php", "history", ALL, "Show a history of a player");
	
	//Smileys
	Command::register($MODULE_NAME, "smileys.php", "smileys", ALL, "The meaning of different Smileys");
	
	//Inspect
	Command::register($MODULE_NAME, "inspect.php", "inspect", ALL, "Inspects Christmas/Eart Gifts and Peren. Containers");
	
	//Alien City Generals
	Command::register($MODULE_NAME, "aigen.php", "aigen", ALL, "Info about Alien City Generals(which VBs they drop)");
	
	//Alien Armor
	Command::register($MODULE_NAME, "aiarmor.php", "aiarmor", ALL, "Tradeskillprocess for Alien Armor");

	//Setup
	Event::register("setup", $MODULE_NAME, "setup.php");

	//Help files
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
?>
