<?php
	$MODULE_NAME = "SPAM_MODULE";
	
	bot::command("priv", "$MODULE_NAME/spam.php", "spam", "all", "Spams a message to linknet");
	
	bot::addsetting($MODULE_NAME, "otspambot", "Omni news relay bot", "edit", "Linknet", "text");
?>