<?php
	$MODULE_NAME = "SVN_MODULE";
	$PLUGIN_VERSION = 1.0;

	Command::register("", $MODULE_NAME, "svn.php", "svn", ADMIN, "Updates your bot from svn repository");
	
	Settings::add("svnconflict", $MODULE_NAME, "How to handle conflicts", "edit", "theirs-conflict", "theirs-conflict;mine-conflict;theirs-full;mine-full;postpone", '0', ADMIN);

?>