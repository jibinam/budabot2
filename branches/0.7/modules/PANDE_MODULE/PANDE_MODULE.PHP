<?php

	$MODULE_NAME = "PANDE_MODULE";
	$PLUGIN_VERSION = 1.0;
	
	// Pande loot manager
	Command::register("", $MODULE_NAME, "pandeloot.php", "beastarmor", ALL, "Shows Possible Beast Armor Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "beastweaps", ALL, "Shows Possible Beast Weapons Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "beaststars", ALL, "Shows Possible Beast Stars Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "tnh", ALL, "Shows Possible The Night Heart Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "sb", ALL, "Shows Possible Shadowbreeds Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "aries", ALL, "Shows Possible Aries Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "leo", ALL, "Shows Possible Leo Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "virgo", ALL, "Shows Possible Virgo Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "aquarius", ALL, "Shows Possible Aquarius Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "cancer", ALL, "Shows Possible Cancer Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "gemini", ALL, "Shows Possible Gemini Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "libra", ALL, "Shows Possible Libra Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "pisces", ALL, "Shows Possible Pisces Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "taurus", ALL, "Shows Possible Taurus Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "capricorn", ALL, "Shows Possible Capricorn Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "sagittarius", ALL, "Shows Possible Sagittarius Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "scorpio", ALL, "Shows Possible Scorpio Zodiac Loot");
	Command::register("", $MODULE_NAME, "pandeloot.php", "pandeloot", LEADER, "used to add pande loot to the loot list");
	Command::register("", $MODULE_NAME, "pandeloot.php", "pande", ALL, "shows Initial list of pande bosses");

	//Help files
	Help::register("pande", $MODULE_NAME, "pande.txt", ALL, "Loot manager for Pandemonium Raid loot");

?>
