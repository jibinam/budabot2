<?php
	 /*
   ** Author: Mindrila (RK1)
   ** Credits: Legendadv (RK2)
   ** BUDABOT IRC NETWORK MODULE
   ** Version = 0.1
   ** Developed for: Budabot(http://budabot.com)
   **
   */
   
global $bbin_socket;
if ("1" == Settings::get('bbin_status')) {
	// do not relay commands and ignored chars
	if ($args[2][0] != Settings::get("symbol") && !Settings::get("Ignore")[$sender]) {
		
		$outmsg = htmlspecialchars($message);
		
		fputs($bbin_socket, "PRIVMSG ".Settings::get('bbin_channel')." :$sender: $message\n");
		if (Settings::get('bbin_debug_messages') == 1) {
			newLine("BBIN"," ","[Out. BBIN Msg.] $sender: $message",0);
		}
	}
}
?>