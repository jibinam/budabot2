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
if($this->settings['bbin_status'] = "1") {
	
	$msg = "[BBIN:LOGOFF:".$sender.",".$this->vars["dimension"].",";
	
	if($type == "joinPriv") {
		$msg .= "1]";
	}
	else {
		$msg .= "0]";
	}
	
	if($type == "leavePriv") {
		flush();
		fputs($bbin_socket, "PRIVMSG ".$this->settings['bbin_channel']." :$msg\n");
		if($this->settings['bbin_debug_messages'] == 1) {
			echo("[".date('H:i')."] [Out. bbin Msg.] $msg\n");
		}
	}
	elseif($type == "logOff" && isset($this->guildmembers[$sender])) {
		flush();
		fputs($bbin_socket, "PRIVMSG ".$this->settings['bbin_channel']." :$msg\n");
		if($this->settings['bbin_debug_messages'] == 1) {
			echo("[".date('H:i')."] [Out. bbin Msg.] $msg\n");
		}
	}
}

?>