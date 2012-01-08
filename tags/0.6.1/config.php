<?
/////////////////////////////////////////////////
	/* **************************************** */
	/* Config file for the bot.					*/
	/* To change this settings you can use the  */
	/* Ingame Commands(config/settings) for it  */
	/* but change these file only when you 		*/
	/* know what you are doing.					*/
	/* **************************************** */
	
	// Insert your Account infos here
	$vars['login'] 			= "";          
	$vars['password'] 		= "";          
	$vars['name'] 			= ""; 
	$vars['my guild'] 		= "";          
	$vars['dimension'] 		= 1;         
	//Insert the Administratorname here
	$settings['Super Admin'] 	= "";          	
    // What prefix should be used for private/Guild channel
	$settings['symbol']			= "!";            	

	// Debug Messages
	// 0 = Show no message
	// 1 = Show basic debug messages
	// 2 = Show enhanced debug messages
	// 3 = Show enhanced debug messages + 1sec delay
	$settings['debug'] 			= 0;       

	// Log Messages
	// 0 = Show nothing
	// 1 = Show them only on the console
	// 2 = Show them on the console and log them to files	
	$settings['echo'] 			= 1;  
	
    // Default Delay for crons after bot is connected
    $settings['CronDelay']      = 30;          
	
    // Default Colors that should be used
	$settings['default guild color']     = "<font color='#84FFFF'>";               
	$settings['default priv color']      = "<font color='#84FFFF'>";          
	$settings['default window color']    = "<font color='#84FFFF'>";          
	$settings['default tell color']      = "<font color='#DDDDDD'>";             
	$settings['default highlight color'] = "<font color='#9CC6E7'>";      
	$settings['default header color']    = "<font color='#FFFF00'>";      
	
	// Replace "Insert Name here" with the bot that
	// tells should be ignored by default
	$settings['Ignore'] = "";         
	
	//Database Informations	
	$settings['DB Type'] = "Sqlite";           //What type of Database should be used? (Sqlite or Mysql)
    $settings['DB Name'] = "budabot.db";           // Database Name
	$settings['DB Host'] = "./data/";           // Hostname or File location.
	$settings['DB username'] = "";           // Mysql User name
    $settings['DB password'] = "";           // Mysql Password

	//Cache folder for storing char or org xml files
    $vars['cachefolder'] = "./cache/";          
    
    //Set lowest needed rank for guild admin
    //President = 0
    //General = 1
    //Squad Commander = 2
    //Unit Commander = 3
    //Unit Leader = 4
    //Unit Member = 5
    //Applicant = 6
    $settings['guild admin level'] = 3;      
    
    // Spam Protection
    // 1 = Spam Protection is enabled
    // 0 = Spam Protection is disabled
	$settings['spam protection'] = 0;      
    
    // Default Status for modules
    // 0 = Modules are disabled by default
    // 1 = Modules are enabled by default
    $settings['default module status'] = 0;

    // Maximum chars for one window(blob) in bytes
    $settings['max_blob_size'] = 7500;       
////////////////////////////////////////////////
?>