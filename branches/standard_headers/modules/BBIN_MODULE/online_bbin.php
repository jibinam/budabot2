<?php
   
global $bbinSocket;
if (preg_match("/^onlinebbin$/i", $message)) {
	if (!IRC::isConnectionActive($bbinSocket)) {
		$chatBot->send("There is no active IRC connection.", $sendto);
		return;
	}

	$names = IRC::getUsersInChannel($bbinSocket, $setting->get('bbin_channel'));
	$numusers = count($names);
	$blob = '';
	forEach ($names as $value) {
		$blob .= "$value\n";
	}
	
	$msg = Text::make_blob("BBIN Online ($numusers)", $blob);
	
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>