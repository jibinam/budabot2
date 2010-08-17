<?php
   /*
   ** Author: Legendadv (RK2)
   ** IRC RELAY MODULE
   **
   ** Developed for: Budabot(http://aodevs.com/index.php/topic,512.0.html)
   **
   */
   
global $socket;
if ("1" == Settings::get('irc_status')) {
	if ($args[2][0] != Settings::get("symbol"] && !Settings::get("Ignore")[$sender] && $irc = "active") {
		
		$patterns = array(
		  '/<a href="itemref:\/\/(\d+)\/\1\/(\d+)">([^<]+)<\/a>/',
		  '/<a href="itemref:\/\/(\d+)\/(\d+)\/(\d+)">([^<]+)<\/a>/',
		);

		$replaces = array(
		  '\3 (http://auno.org/ao/db.php?id=\1)',
		  '\4 (http://auno.org/ao/db.php?id=\1&ql=\3)',
		);

		$msg = htmlspecialchars_decode(preg_replace($patterns, $replaces, $message));

		fputs($socket, "PRIVMSG ".Settings::get('irc_channel')." :$sender: $msg\n");
		if (Settings::get('irc_debug_messages') == 1) {
			Logger::log_chat("IRC Out. Msg.", $sender, $msg);
		}
	}
}
?>