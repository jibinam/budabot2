<?php

if (strtolower($sender) == strtolower(Setting::get('shopbot_master'))) {
	return;
} else if (!AccessLevel::check_access($sender, 'member')) {
	$senderLink = Text::make_userlink($sender);
	$chatBot->sendPrivate("<green>[Inc. Msg.]<end> {$senderLink}: <green>{$message}<end>", Setting::get('shopbot_master'));

	// we don't want the bot to respond back to people
	$sender = null;
	$stop_execution = true;
}

?>
