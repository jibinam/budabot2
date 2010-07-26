<?php 
	$MODULE_NAME = "ADMIN";
	
	//Setup
	Event::register("setup", $MODULE_NAME, "upload_admins.php");

	//Commands	
	Command::register("msg", $MODULE_NAME, "addadmin.php", "addadmin", SUPERADMIN);
	Command::register("priv", $MODULE_NAME, "addadmin.php", "addadmin", SUPERADMIN);
	Command::register("guild", $MODULE_NAME, "addadmin.php", "addadmin", SUPERADMIN);
	
	Command::register("msg", $MODULE_NAME, "kickadmin.php", "kickadmin", SUPERADMIN);
	Command::register("priv", $MODULE_NAME, "kickadmin.php", "kickadmin", SUPERADMIN);
	Command::register("guild", $MODULE_NAME, "kickadmin.php", "kickadmin", SUPERADMIN);
	
	Command::register("msg", $MODULE_NAME, "addmod.php", "addmod", ADMIN);
	Command::register("priv", $MODULE_NAME, "addmod.php", "addmod", ADMIN);
	Command::register("guild", $MODULE_NAME, "addmod.php", "addmod", ADMIN);
	
	Command::register("msg", $MODULE_NAME, "kickmod.php", "kickmod", ADMIN);
	Command::register("priv", $MODULE_NAME, "kickmod.php", "kickmod", ADMIN);
	Command::register("guild", $MODULE_NAME, "kickmod.php", "kickmod", ADMIN);
	
	Command::register("msg", $MODULE_NAME, "raidleader.php", "raidleader", MODERATOR);
	Command::register("priv", $MODULE_NAME, "raidleader.php", "raidleader", MODERATOR);
	Command::register("guild", $MODULE_NAME, "raidleader.php", "raidleader", MODERATOR);
	
	Command::register("msg", $MODULE_NAME, "kickraidleader.php", "kickraidleader", MODERATOR);
	Command::register("priv", $MODULE_NAME, "kickraidleader.php", "kickraidleader", MODERATOR);
	Command::register("guild", $MODULE_NAME, "kickraidleader.php", "kickraidleader", MODERATOR);

	Command::register("msg", $MODULE_NAME, "adminlist.php", "adminlist");
	Command::register("priv", $MODULE_NAME, "adminlist.php", "adminlist");
	Command::register("guild", $MODULE_NAME, "adminlist.php", "adminlist");

	//Events
	Event::register("logOn", $MODULE_NAME, "admin_logon.php");
	Event::register("logOff", $MODULE_NAME, "admin_logoff.php");
	Event::register("24hrs", $MODULE_NAME, "check_admins.php");

	//Help Files
	Help::register("adminhelp", $MODULE_NAME, "admin.txt", MODERATOR, "Mod/Admin Help file.");
?>