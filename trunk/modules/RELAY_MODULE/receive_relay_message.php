<?php

if (($sender == ucfirst(strtolower($this->settings['relaybot'])) || $channel == ucfirst(strtolower($this->settings['relaybot']))) && preg_match("/^grc (.+)$/s", $message, $arr)) {
	$msg = $arr[1];
    $chatBot->send($msg, "guild", true);

	if ($this->settings["guest_relay"] == 1) {
		$chatBot->send($msg, "priv", true);
	}
} else {
	$syntax_error = true;
}

?>