<?php
$MODULE_NAME = "HELPBOT_MODULE";
$PLUGIN_VERSION = 0.1;
$FOLDER = $dir;

	//Time Part
	bot::command("all", "$MODULE_NAME/time.php", "time", "all", "Shows the time in the different timezones");
	bot::command("msg", "$MODULE_NAME/time.php", "time", "all", "Shows the time in the different timezones");
	bot::command("priv", "$MODULE_NAME/time.php", "time", "all", "Shows the time in the different timezones");

    //Whois Part
	bot::command("all", "$MODULE_NAME/whois.php", "whois", "all", "Char Infos (only current dim)");
	bot::command("msg", "$MODULE_NAME/whois.php", "whois", "all", "Char Infos (only current dim)");
	bot::command("priv", "$MODULE_NAME/whois.php", "whois", "all", "Char Infos (only current dim)");

    //Whoisall Part
	bot::command("all", "$MODULE_NAME/whois.php", "whoisall", "all", "Char Infos (all dim)");
	bot::command("msg", "$MODULE_NAME/whois.php", "whoisall", "all", "Char Infos(all dim)");
	bot::command("priv", "$MODULE_NAME/whois.php", "whoisall", "all", "Char Infos(all dim)");

	//Whoisorg Part
	bot::command("all", "$MODULE_NAME/whois.php", "whoisorg", "all", "Org Infos");
	bot::command("msg", "$MODULE_NAME/whois.php", "whoisorg", "all", "Org Infos");
	bot::command("priv", "$MODULE_NAME/whois.php", "whoisorg", "all", "Org Infos");

    //PVP Ranges
	bot::command("all", "$MODULE_NAME/pvp_ranges.php", "pvp", "all", "Show PVP ranges");
	bot::command("msg", "$MODULE_NAME/pvp_ranges.php", "pvp", "all", "Show PVP ranges");
	bot::command("priv", "$MODULE_NAME/pvp_ranges.php", "pvp", "all", "Show PVP ranges");

    //Level Infos
	bot::command("all", "$MODULE_NAME/levels.php", "level", "all", "Show level ranges");
	bot::command("msg", "$MODULE_NAME/levels.php", "level", "all", "Show level ranges");
	bot::command("priv", "$MODULE_NAME/levels.php", "level", "all", "Show level ranges");
	bot::command("all", "$MODULE_NAME/levels.php", "lvl", "all", "Show level ranges");
	bot::command("msg", "$MODULE_NAME/levels.php", "lvl", "all", "Show level ranges");
	bot::command("priv", "$MODULE_NAME/levels.php", "lvl", "all", "Show level ranges");
	bot::regGroup("lvlrng", $MODULE_NAME, "Show level ranges", "lvl", "level");

	//Missions
	bot::command("all", "$MODULE_NAME/missions.php", "mission", "all");
	bot::command("msg", "$MODULE_NAME/missions.php", "mission", "all");
	bot::command("priv", "$MODULE_NAME/missions.php", "mission", "all");
	
	//Biomaterial identification
	bot::command("all", "$MODULE_NAME/biomat_identify.php", "bio", "all", "Biomaterial Identify");
	bot::command("msg", "$MODULE_NAME/biomat_identify.php", "bio", "all", "Biomaterial Identify");
	bot::command("priv", "$MODULE_NAME/biomat_identify.php", "bio", "all", "Biomaterial Identify");
	
	//Calculator
	bot::command("all", "$MODULE_NAME/calc.php", "calc", "all", "Calculator");
	bot::command("msg", "$MODULE_NAME/calc.php", "calc", "all", "Calculator");
	bot::command("priv", "$MODULE_NAME/calc.php", "calc", "all", "Calculator");

	//OE Calculator
	bot::command("all", "$MODULE_NAME/oe.php", "oe", "all", "OE");
	bot::command("msg", "$MODULE_NAME/oe.php", "oe", "all", "OE");
	bot::command("priv", "$MODULE_NAME/oe.php", "oe", "all", "OE");

	//XP/SK/AXP Calculator
	bot::command("all", "$MODULE_NAME/xp_sk_calc.php", "sk", "all", "SK Calculator");
	bot::command("msg", "$MODULE_NAME/xp_sk_calc.php", "sk", "all", "SK Calculator");
	bot::command("priv", "$MODULE_NAME/xp_sk_calc.php", "sk", "all", "SK Calculator");
	
	bot::command("all", "$MODULE_NAME/xp_sk_calc.php", "xp", "all", "XP Calculator");
	bot::command("msg", "$MODULE_NAME/xp_sk_calc.php", "xp", "all", "XP Calculator");
	bot::command("priv", "$MODULE_NAME/xp_sk_calc.php", "xp", "all", "XP Calculator");

	bot::command("all", "$MODULE_NAME/axp.php", "axp", "all", "AXP Calculator");
	bot::command("msg", "$MODULE_NAME/axp.php", "axp", "all", "AXP Calculator");
	bot::command("priv", "$MODULE_NAME/axp.php", "axp", "all", "AXP Calculator"); 
	bot::regGroup("EXP", $MODULE_NAME, "Calculate needed XP/SK/AXP", "sk", "xp", "axp");

	//Flip or Roll command
	bot::command("msg", "$MODULE_NAME/roll.php", "flip", "all", "Flip a coin"); 
	bot::command("msg", "$MODULE_NAME/roll.php", "roll", "all", "Rolls a number between given ranges");
	bot::command("priv", "$MODULE_NAME/roll.php", "flip", "all", "Flip a coin"); 
	bot::command("priv", "$MODULE_NAME/roll.php", "roll", "all", "Rolls a number between given ranges");
	bot::command("all", "$MODULE_NAME/roll.php", "flip", "all", "Flip a coin"); 
	bot::command("all", "$MODULE_NAME/roll.php", "roll", "all", "Rolls a number between given ranges");
	bot::command("msg", "$MODULE_NAME/roll.php", "verify", "all", "Verifys a flip or roll");
	
	//Player History
	bot::command("msg", "$MODULE_NAME/player_history.php", "history", "all", "Show a history of a player");	
	bot::command("priv", "$MODULE_NAME/player_history.php", "history", "all", "Show a history of a player");	
	bot::command("all", "$MODULE_NAME/player_history.php", "history", "all", "Show a history of a player");	
	
	//Whereis
	bot::command("msg", "$MODULE_NAME/whereis.php", "whereis", "all", "Show where places/uniques are on RK");	
	bot::command("priv", "$MODULE_NAME/whereis.php", "whereis", "all", "Show where places/uniques are on RK");	
	bot::command("all", "$MODULE_NAME/whereis.php", "whereis", "all", "Show where places/uniques are on RK");	
	
	//Title Levels
	bot::command("msg", "$MODULE_NAME/title.php", "title", "all", "Show the Titlelevels and how much IP/Level");	
	bot::command("priv", "$MODULE_NAME/title.php", "title", "all", "Show the Titlelevels and how much IP/Level");	
	bot::command("all", "$MODULE_NAME/title.php", "title", "all", "Show the Titlelevels and how much IP/Level");	

	//Smileys
	bot::command("msg", "$MODULE_NAME/smileys.php", "smileys", "all", "The meaning of different Smileys");	
	bot::command("priv", "$MODULE_NAME/smileys.php", "smileys", "all", "The meaning of different Smileys");	
	bot::command("all", "$MODULE_NAME/smileys.php", "smileys", "all", "The meaning of different Smileys");	
	
	//Inspect
	bot::command("msg", "$MODULE_NAME/inspect.php", "inspect", "all", "Inspects Christmas/Eart Gifts and Peren. Containers");	
	bot::command("priv", "$MODULE_NAME/inspect.php", "inspect", "all", "Inspects Christmas/Eart Gifts and Peren. Containers");	
	bot::command("all", "$MODULE_NAME/inspect.php", "inspect", "all", "Inspects Christmas/Eart Gifts and Peren. Containers");
	
	//Alien City Generals
	bot::command("msg", "$MODULE_NAME/aigen.php", "aigen", "all", "Info about Alien City Generals(which VB큦 they drop)");	
	bot::command("priv", "$MODULE_NAME/aigen.php", "aigen", "all", "Info about Alien City Generals(which VB큦 they drop)");	
	bot::command("all", "$MODULE_NAME/aigen.php", "aigen", "all", "Info about Alien City Generals(which VB큦 they drop)");	
	
	//Alien Armor
	bot::command("msg", "$MODULE_NAME/aiarmor.php", "aiarmor", "all", "Tradeskillprocess for Alien Armor");	
	bot::command("priv", "$MODULE_NAME/aiarmor.php", "aiarmor", "all", "Tradeskillprocess for Alien Armor");	
	bot::command("all", "$MODULE_NAME/aiarmor.php", "aiarmor", "all", "Tradeskillprocess for Alien Armor");	

	//Setup
	bot::event("setup", "$MODULE_NAME/setup.php");

	//Helpfiles
	bot::help("whois", "$MODULE_NAME/whois.txt", "all", "Show char stats at current and all dimensions", "Helpbot");
	bot::help("pvpranges", "$MODULE_NAME/pvpranges.txt", "all", "Pvp ranges", "Helpbot");
    bot::help("level", "$MODULE_NAME/level.txt", "all", "Levelinfos", "Helpbot");
    bot::help("title_level", "$MODULE_NAME/title.txt", "all", "Infos about TitleLevels", "Helpbot");
    bot::help("whereis", "$MODULE_NAME/whereis.txt", "all", "Show infos about NPC큦 and Locations", "Helpbot");
    bot::help("missions", "$MODULE_NAME/missions.txt", "all", "Who can roll a specific QL of a mission", "Helpbot");    
    bot::help("biomat", "$MODULE_NAME/biomat.txt", "all", "Identify an Biomaterial", "Helpbot");
    bot::help("calculator", "$MODULE_NAME/calculator.txt", "all", "Calculator", "Helpbot");
    bot::help("oe", "$MODULE_NAME/oe.txt", "all", "Calculating the OE ranges", "Helpbot");
	bot::help("experience", "$MODULE_NAME/experience.txt", "all", "XP/SK/AXP Infos", "Helpbot");
    bot::help("fliproll", "$MODULE_NAME/fliproll.txt", "all", "How to use the flip and roll command", "Helpbot");
    bot::help("history", "$MODULE_NAME/history.txt", "all", "History of a player", "Helpbot");
    bot::help("time", "$MODULE_NAME/time.txt", "all", "Timezones", "Helpbot");
    bot::help("kos_list", "$MODULE_NAME/kos_list.txt", "all", "Kill On Sight List", "Helpbot");
    bot::help("smiley_title_inspect", "$MODULE_NAME/smiley_title_inspect.txt", "all", "Help for Smiley,Title Level and Inspect", "Helpbot");
    bot::help("alien_armor", "$MODULE_NAME/alien_armor.txt", "all", "Alien armor Tradeskillprocess", "Helpbot");
	bot::help("alien_generals", "$MODULE_NAME/alien_generals.txt", "all", "Alien City Generals Info", "Helpbot");    
?>
