<?php 
	$MODULE_NAME = "SYSTEM";

	//Commands
	Command::activate("msg", "$MODULE_NAME/restart.php", "restart", "admin");
	Command::activate("priv", "$MODULE_NAME/restart.php", "restart", "admin");
	Command::activate("guild", "$MODULE_NAME/restart.php", "restart", "admin");

	Command::activate("msg", "$MODULE_NAME/shutdown.php", "shutdown", "admin");
	Command::activate("priv", "$MODULE_NAME/shutdown.php", "shutdown", "admin");
	Command::activate("guild", "$MODULE_NAME/shutdown.php", "shutdown", "admin");
	
	Command::activate("msg", "$MODULE_NAME/reload_config.php", "reloadconfig", "admin");
	Command::activate("priv", "$MODULE_NAME/reload_config.php", "reloadconfig", "admin");
	Command::activate("guild", "$MODULE_NAME/reload_config.php", "reloadconfig", "admin");

	Command::activate("msg", "$MODULE_NAME/system_cmd.php", "system", "mod");
	Command::activate("priv", "$MODULE_NAME/system_cmd.php", "system", "mod");
	Command::activate("guild", "$MODULE_NAME/system_cmd.php", "system", "mod");

	Command::activate("msg", "$MODULE_NAME/cmdlist.php", "cmdlist", "guild");
	Command::activate("priv", "$MODULE_NAME/cmdlist.php", "cmdlist", "guild");
	Command::activate("guild", "$MODULE_NAME/cmdlist.php", "cmdlist", "guild");

	Command::activate("msg", "$MODULE_NAME/boteventlist.php", "boteventlist", "mod");
	Command::activate("priv", "$MODULE_NAME/boteventlist.php", "boteventlist", "mod");
	Command::activate("guild", "$MODULE_NAME/boteventlist.php", "boteventlist", "mod");

	Command::activate("msg", "$MODULE_NAME/lookup.php", "lookup", "all");
	Command::activate("priv", "$MODULE_NAME/lookup.php", "lookup", "all");
	Command::activate("guild", "$MODULE_NAME/lookup.php", "lookup", "all");
	
	Command::activate("msg", "$MODULE_NAME/clearqueue.php", "clearqueue", "mod");
	Command::activate("priv", "$MODULE_NAME/clearqueue.php", "clearqueue", "mod");
	Command::activate("guild", "$MODULE_NAME/clearqueue.php", "clearqueue", "mod");
	
	Command::activate("msg", "$MODULE_NAME/loadsql.php", "loadsql", "mod");
	Command::activate("priv", "$MODULE_NAME/loadsql.php", "loadsql", "mod");
	Command::activate("guild", "$MODULE_NAME/loadsql.php", "loadsql", "mod");
	
	Command::activate("msg", "$MODULE_NAME/executesql.php", "executesql", "admin");
	Command::activate("priv", "$MODULE_NAME/executesql.php", "executesql", "admin");
	Command::activate("guild", "$MODULE_NAME/executesql.php", "executesql", "admin");
	
	Command::activate("msg", "$MODULE_NAME/checkaccess.php", "checkaccess", "all");
	Command::activate("priv", "$MODULE_NAME/checkaccess.php", "checkaccess", "all");
	Command::activate("guild", "$MODULE_NAME/checkaccess.php", "checkaccess", "all");
	
	Command::activate("msg", "$MODULE_NAME/logs.php", "logs", "admin");
	Command::activate("priv", "$MODULE_NAME/logs.php", "logs", "admin");
	Command::activate("guild", "$MODULE_NAME/logs.php", "logs", "admin");

	Event::activate("1hour", "$MODULE_NAME/ping_db.php");
	Event::activate("2sec", "$MODULE_NAME/reduce_spam_values.php");
	Event::activate("1min", "$MODULE_NAME/reduce_largespam_values.php");
	Event::activate("connect", "$MODULE_NAME/systems_ready.php");
	
	Setting::add($MODULE_NAME, 'symbol', 'Command prefix symbol', 'edit', "text", '!', '!;#;*;@;$;+;-', '', 'mod');
	Setting::add($MODULE_NAME, 'guild_admin_level', 'Guild admin level', 'edit', "number", 1, 'President;General;Squad Commander;Unit Commander;Unit Leader;Unit Member;Applicant', '0;1;2;3;4;5;6', 'mod');
	Setting::add($MODULE_NAME, 'spam_protection', 'Enable spam protection', 'edit', "options", 0, "true;false", "1;0", 'mod');
	Setting::add($MODULE_NAME, 'max_blob_size', 'Max chars for a window', 'edit', "number", 7500, '4500;6000;7500;9000;10500;12000', '', 'mod');
	Setting::add($MODULE_NAME, 'logon_delay', 'Seconds to wait before executing connect events and cron jobs', 'edit', "number", 10, '5;10;20;30', '', 'mod');
	Setting::add($MODULE_NAME, 'guild_channel_status', 'Enable the guild channel', 'edit', "options", 1, "true;false", "1;0", 'mod');
	Setting::add($MODULE_NAME, 'guild_channel_cmd_feedback', "Show message on invalid command in guild channel", 'edit', "options", 1, "true;false", "1;0", 'mod');
	Setting::add($MODULE_NAME, 'private_channel_cmd_feedback', "Show message on invalid command in private channel", 'edit', "options", 1, "true;false", "1;0", 'mod');

	//Help Files
	Help::register($MODULE_NAME, "system", "system.txt", "admin", "Admin System Help file");
	Help::register($MODULE_NAME, "guild_admin_level", "guild_admin_level.txt", "mod", "Change what guild rank and high receives the guild admin level privilege");
	Help::register($MODULE_NAME, "spam_protection", "spam_protection.txt", "mod", "Enable or disable the spam protection");
	Help::register($MODULE_NAME, "max_blob_size", "max_blob_size.txt", "mod", "Set the maximum blob size");
	// TODO add help for logs, checkaccess, executesql, loadsql, clearqueue, lookup, eventlist, cmdlist, reloadconfig
?>