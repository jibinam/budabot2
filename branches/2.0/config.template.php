<?php
/////////////////////////////////////////////////
	/* ********************************************	*/
	/* Config file for the bot.			*/
	/* To change this settings you can use the	*/
	/* Ingame Commands(config/settings) for it	*/
	/* but change these file only when you		*/
	/* know what you are doing.			*/
	/* ********************************************	*/

	// Insert your Account infos here
	$vars['login']		= "";
	$vars['password']	= "";
	$vars['name']		= "";
	$vars['my guild']	= "";
	$vars['dimension']	= 1;  // 1 for Atlantean, 2 for Rimor, 3 for Die Nueue Welt, 4 for Testlive

	// Insert the Administrator name here
	$vars['SuperAdmin'] = "";

	// What prefix should be used for private/Guild channel
	$settings['symbol'] = "!";

	// Logging level 
	// DEBUG
	// DETAIL
	// INFO
	// WARN
	// ERROR
	// FATAL
	$vars['console_log_level'] = INFO;
	$vars['file_log_level'] = INFO;

	// Default Delay for crons after bot is connected
	$settings['CronDelay'] = 0;

	// Other bots that this bot should ignore
	$settings['Ignore'] = "";

	// Database Informations	
	$settings['DB Type'] = "Sqlite";	// What type of Database should be used? (Sqlite or Mysql)
	$settings['DB Name'] = "budabot.db";	// Database Name
	$settings['DB Host'] = "./data/";	// Hostname or File location.
	$settings['DB username'] = "";		// Mysql User name
	$settings['DB password'] = "";		// Mysql Password

	// Cache folder for storing char or org xml files
	$vars['cachefolder'] = "./cache/";

	// Set lowest needed rank for guild admin
	// President		Director	= 0
	// General		Board Member	= 1
	// Squad Commander	Executive	= 2
	// Unit Commander	Member		= 3
	// Unit Leader		Applicant	= 4
	// Unit Member				= 5
	// Applicant				= 6
	$settings['guild_admin_level'] = 3;

	// Spam Protection
	// 1 = Spam Protection is enabled
	// 0 = Spam Protection is disabled
	$settings['spam_protection'] = 0;

	// Default Status for modules
	// 0 = Modules are disabled by default
	// 1 = Modules are enabled by default
	$settings['default_module_status'] = 0;

	// Maximum chars for one window(blob) in bytes
	$settings['max_blob_size'] = 7500;
////////////////////////////////////////////////
?>
