<?php

if ((Settings::get("relaybot") != "Off") && ($args[2][0] != Settings::get("symbol"))) {
	$relayMessage = '';
	if (Settings::get('relaysymbol') == 'Always relay') {
		$relayMessage = $message;
	} else if ($args[2][0] == Settings::get('relaysymbol')) {
		$relayMessage = substr($args[2], 1);
	}

	if ($relayMessage != '') {
		$msg = "grc <grey>[".$this->vars["my guild"]."] ".Text::makeLink($sender,$sender,"user").": ".$relayMessage."</font>";
        send_message_to_relay($msg);
	}
}

?>