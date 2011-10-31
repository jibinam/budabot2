<?php

global $ircSocket;
if (preg_match("/^stopirc$/i", $message, $arr)) {
	if (!IRC::isConnectionActive($ircSocket)) {
		$chatBot->send("There is no active IRC connection.", $sendto);
	} else {
		IRC::disconnect($ircSocket);
		Logger::log('info', "IRC", "Disconnected from IRC");
		Setting::save("irc_status", "0");
		$chatBot->send("The IRC connection has been disconnected.", $sendto);
	}
} else {
	$syntax_error = true;
}

?>
