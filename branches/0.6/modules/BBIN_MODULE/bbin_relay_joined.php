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
if("1" == $this->settings['bbin_status']) {
	$msg = "[BBIN:LOGON:".$sender.",".$this->vars["dimension"].",";
	
	if($type == "joinPriv") {
		$msg .= "1]";
	}
	else {
		$msg .= "0]";
	}
	
	if($type == "joinPriv") {
		fputs($bbin_socket, "PRIVMSG ".$this->settings['bbin_channel']." :$msg\n");
		if($this->settings['bbin_debug_messages'] == 1) {
			newLine("BBIN"," ","[Out. bbin Msg.] $msg",0);
		}
	}
	elseif(isset($this->guildmembers[$sender])) {
		fputs($bbin_socket, "PRIVMSG ".$this->settings['bbin_channel']." :$msg\n");
		if($this->settings['bbin_debug_messages'] == 1) {
			newLine("BBIN"," ","[Out. bbin Msg.] $msg",0);
		}
	}
}

?>
