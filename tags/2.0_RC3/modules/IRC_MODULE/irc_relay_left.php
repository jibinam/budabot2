<?php
   /*
   ** Author: Legendadv (RK2)
   ** IRC RELAY MODULE
   **
   ** Developed for: Budabot(http://aodevs.com/index.php/topic,512.0.html)
   **
   */
   
global $socket;
if("1" == $this->settings['irc_status']) {
	if($type == "leavePriv") {
		flush();
		fputs($socket, "PRIVMSG ".$this->settings['irc_channel']." :$sender has left the private chat.\n");
		if($this->settings['irc_debug_messages'] == 1) {
			Logger::log_chat("Out. IRC Msg.", -1, "$sender has left the channel");
		}
	}
	elseif($type == "logOff" && isset($this->guildmembers[$sender])) {
		flush();
		fputs($socket, "PRIVMSG ".$this->settings['irc_channel']." :$sender has logged off.\n");
		if($this->settings['irc_debug_messages'] == 1) {
			Logger::log_chat("Out. IRC Msg.", -1, "$sender has left the channel");
		}	
	}
}

?>