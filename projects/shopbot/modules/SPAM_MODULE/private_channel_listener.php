<?php

if ($message[0] == Setting::get('symbol') && $type == 'extPriv' && strtolower($channel) == strtolower(Setting::get('shopbot_master'))) {
	$message = substr($message, 1);
	$chatBot->process_command("priv", $message, $sender, $sender);
}

?>