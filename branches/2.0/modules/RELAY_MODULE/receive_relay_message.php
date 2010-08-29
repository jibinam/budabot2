<?php

if (($sender == ucfirst(strtolower(Settings::get('relaybot'))) || $channel == ucfirst(strtolower(Settings::get('relaybot')))) && preg_match("/^grc (.+)$/", $message, $arr)) {
	$msg = $arr[1];
    $chatBot->send($msg, "guild", true);

	if (Settings::get("guest_relay") == 1) {
		$chatBot->send($msg, "priv", true);
	}
}

?>