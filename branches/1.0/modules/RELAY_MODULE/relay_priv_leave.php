<?php

if ($type == "leavePriv" && $this->settings['relaybot'] != 'Off') {
	$msg = "<highlight>{$sender}<end> has left the private channel.";
	send_message_to_relay("grc <grey>[".$this->vars['my guild']."] ".$msg);
}

?>