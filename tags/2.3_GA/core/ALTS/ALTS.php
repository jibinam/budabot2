<?php
	require_once 'Alts.class.php';
	
	DB::loadSQLFile($MODULE_NAME, "alts");
	
	Event::register($MODULE_NAME, "logOn", "check_unvalidated_alts.php", "", "Reminds players logging in to validate alts");
	
	Command::register($MODULE_NAME, "", "altvalidate.php", "altvalidate", "member", "Validate alts for admin privileges");
	Command::register($MODULE_NAME, "", "altsadmin.php", "altsadmin", "mod", "Alt character handling (admin)");
	Command::register($MODULE_NAME, "", "altscmd.php", "alts", "member", "Alt character handling");
	CommandAlias::register($MODULE_NAME, "alts", "alt");

	Subcommand::register($MODULE_NAME, "", "alts_main.php", "alts main (.+)", "member", "alts", "Add yourself as an alt to a main", 'alts');

	Setting::add($MODULE_NAME, 'alts_inherit_admin', 'Alts inherit admin privileges from main', 'edit', "options", 0, "true;false", "1;0", 'mod');
	Setting::add($MODULE_NAME, "validate_from_validated_alt", "Validate alts from any validated alt", "edit", "options", "1", "true;false", "1;0");
	
	Help::register($MODULE_NAME, "alts", "alts.txt", "member", "How to set alts");
	Help::register($MODULE_NAME, "altsadmin", "altsadmin.txt", "mod", "How to set alts (admin)");
	Help::register($MODULE_NAME, "altvalidate", "altvalidate.txt", "member", "How to validate alts");
?>