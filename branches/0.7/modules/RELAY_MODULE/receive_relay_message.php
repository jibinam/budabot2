<?php

if (($sender == ucfirst(strtolower(Settings::get('relaybot'))) || $channel == ucfirst(strtolower(Settings::get('relaybot')))) && preg_match("/^grc (.+)$/", $message, $arr)) {
	$msg = $arr[1];
    $this->send($msg, "guild", true);

	if (Settings::get("guest_relay") == 1) {
		$this->send($msg, "priv", true);
	}
}

?>