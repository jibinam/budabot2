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
	
	$msg = "[BBIN:LOGOFF:".$sender.",".$this->vars["dimension"].",";
	
	if($type == "joinPriv") {
		$msg .= "1]";
	} else {
		$msg .= "0]";
	}
	
	if ($type == "leavePriv") {
		flush();
		fputs($bbin_socket, "PRIVMSG ".Settings::get('bbin_channel')." :$msg\n");
		if (Settings::get('bbin_debug_messages') == 1) {
			Logger::log_chat("BBIN Out. Msg.", $sender, $msg);
		}
	} else if ($type == "logOff" && isset($this->guildmembers[$sender])) {
		flush();
		fputs($bbin_socket, "PRIVMSG ".Settings::get('bbin_channel')." :$msg\n");
		if (Settings::get('bbin_debug_messages') == 1) {
			Logger::log_chat("BBIN Out. Msg.", $sender, $msg);
		}
	}
}

?>