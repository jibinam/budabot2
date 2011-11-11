<?php 
	Command::activate("msg", "$MODULE_NAME/addadmin.php", "addadmin", "admin");
	Command::activate("priv", "$MODULE_NAME/addadmin.php", "addadmin", "admin");
	Command::activate("guild", "$MODULE_NAME/addadmin.php", "addadmin", "admin");
	
	Command::activate("msg", "$MODULE_NAME/remadmin.php", "remadmin", "superadmin");
	Command::activate("priv", "$MODULE_NAME/remadmin.php", "remadmin", "superadmin");
	Command::activate("guild", "$MODULE_NAME/remadmin.php", "remadmin", "superadmin");
	
	Command::activate("msg", "$MODULE_NAME/addmod.php", "addmod", "admin");
	Command::activate("priv", "$MODULE_NAME/addmod.php", "addmod", "admin");
	Command::activate("guild", "$MODULE_NAME/addmod.php", "addmod", "admin");
	
	Command::activate("msg", "$MODULE_NAME/remmod.php", "remmod", "admin");
	Command::activate("priv", "$MODULE_NAME/remmod.php", "remmod", "admin");
	Command::activate("guild", "$MODULE_NAME/remmod.php", "remmod", "admin");
	
	Command::activate("msg", "$MODULE_NAME/addrl.php", "addrl", "mod");
	Command::activate("priv", "$MODULE_NAME/addrl.php", "addrl", "mod");
	Command::activate("guild", "$MODULE_NAME/addrl.php", "addrl", "mod");
	
	Command::activate("msg", "$MODULE_NAME/remrl.php", "remrl", "mod");
	Command::activate("priv", "$MODULE_NAME/remrl.php", "remrl", "mod");
	Command::activate("guild", "$MODULE_NAME/remrl.php", "remrl", "mod");

	Command::activate("msg", "$MODULE_NAME/adminlist.php", "adminlist");
	Command::activate("priv", "$MODULE_NAME/adminlist.php", "adminlist");
	Command::activate("guild", "$MODULE_NAME/adminlist.php", "adminlist");

	Event::activate("connect", "$MODULE_NAME/check_admins.php");
	Event::activate("setup", "$MODULE_NAME/upload_admins.php");

	Help::register($MODULE_NAME, "admin", "admin.txt", "mod", "Mod/admin help file");
	Help::register($MODULE_NAME, "alts_inherit_admin", "alts_inherit_admin.txt", "mod", "Alts inherit admin privileges from main");
?>