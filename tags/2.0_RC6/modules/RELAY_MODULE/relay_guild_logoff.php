<?php

if ($chatBot->settings["relaybot"] != "Off" && isset($chatBot->guildmembers[$sender]) && $chatBot->is_ready()) {
	send_message_to_relay("grc <grey>[".$chatBot->vars["my guild"]."] <highlight>{$sender}<end> logged off");
}

?>