<?php
   /*
   ** Author: Legendadv (RK2)
   ** IRC RELAY MODULE
   **
   ** Developed for: Budabot(http://aodevs.com/index.php/topic,512.0.html)
   **
   */
   
global $socket;
if("1" == Settings::get('irc_status')) {
	if($type == "leavePriv") {
		flush();
		fputs($socket, "PRIVMSG ".Settings::get('irc_channel')." :$sender has left the private chat.\n");
		if(Settings::get('irc_debug_messages') == 1) {
			newLine("IRC","irc msg","[Out. IRC Msg.] $sender has left the channel",0);
		}
	}
	elseif($type == "logOff" && isset($this->guildmembers[$sender])) {
		flush();
		fputs($socket, "PRIVMSG ".Settings::get('irc_channel')." :$sender has logged off.\n");
		if(Settings::get('irc_debug_messages') == 1) {
			newLine("IRC","irc msg","[Out. IRC Msg.] $sender has left the channel",0);
		}	
	}
}

?>