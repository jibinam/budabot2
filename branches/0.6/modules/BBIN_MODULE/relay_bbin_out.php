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
	// do not relay commands and ignored chars
	if($args[2][0] != $this->settings["symbol"] && !$this->settings["Ignore"][$sender]) {
		
		$outmsg = htmlspecialchars($message);
		
		fputs($bbin_socket, "PRIVMSG ".$this->settings['bbin_channel']." :$sender: $message\n");
		if($this->settings['bbin_debug_messages'] == 1) {
			echo("[".date('H:i')."] [Out. BBIN Msg.] $sender: $message\n");
		}
	}
}
?>