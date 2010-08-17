<?php 
	$MODULE_NAME = "ADMIN";
	
	//Setup
	Event::register("setup", $MODULE_NAME, "upload_admins.php", '', 1);

	//Commands	
	Command::register($MODULE_NAME, "addadmin.php", "addadmin", SUPERADMIN, 'add admin', 1);
	Command::register($MODULE_NAME, "kickadmin.php", "kickadmin", SUPERADMIN, 'remove admin', 1);
	Command::register($MODULE_NAME, "addmod.php", "addmod", ADMIN, 'add moderator', 1);
	Command::register($MODULE_NAME, "kickmod.php", "kickmod", ADMIN, 'remove moderator', 1);
	Command::register($MODULE_NAME, "raidleader.php", "raidleader", MODERATOR, 'add raidleader', 1);
	Command::register($MODULE_NAME, "kickraidleader.php", "kickraidleader", MODERATOR, 'remove raidleader', 1);
	Command::register($MODULE_NAME, "adminlist.php", "adminlist", ALL, 'shows the admins, moderators, and raidleaders', 1);

	//Events
	Event::register("logOn", $MODULE_NAME, "admin_logon.php", 'Admin logon', 1);
	Event::register("logOff", $MODULE_NAME, "admin_logoff.php", 'Admin logoff', 1);
	Event::register("24hrs", $MODULE_NAME, "check_admins.php", 'Check admins', 1);

	//Help Files
	Help::register($MODULE_NAME, "admin.txt", "adminhelp", MODERATOR, "Mod/Admin Help file.");
?>