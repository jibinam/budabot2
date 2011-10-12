<?php

function spam_shopping_message($message, $channel, $side = 'both') {
	global $chatBot;
	Logger::log('DEBUG', 'SPAM_MODULE', "Sending spam => $channel $side: '$message'");

	if ($channel == 'shopping') {
		if ($side == 'omni') {
			$chatBot->send($message, "OT shopping 11-50");
		} else if ($side == 'clan') {
			$chatBot->send($message, "Clan shopping 11-50");
		} else if ($side == 'neut') {
			$chatBot->send($message, "Neu. shopping 11-50");
		} else if ($side == 'both') {
			$chatBot->send($message, "OT shopping 11-50");
			$chatBot->send($message, "Clan shopping 11-50");
		} else if ($side == 'all') {
			$chatBot->send($message, "OT shopping 11-50");
			$chatBot->send($message, "Clan shopping 11-50");
			$chatBot->send($message, "Neu. shopping 11-50");
		}
	} else if ($channel == 'ooc') {
		if ($side == 'omni') {
			$chatBot->send($message, "OT OOC");
		} else if ($side == 'clan') {
			$chatBot->send($message, "Clan OOC");
		} else if ($side == 'neut') {
			$chatBot->send($message, "Neu. OOC");
		} else if ($side == 'both') {
			$chatBot->send($message, "OT OOC");
			$chatBot->send($message, "Clan OOC");
		} else if ($side == 'all') {
			$chatBot->send($message, "OT OOC");
			$chatBot->send($message, "Clan OOC");
			$chatBot->send($message, "Neu. OOC");
		}
	}
}

function time_left_for_spam_protection($sender) {
	global $shopping_spam_protection;

	$time_between_messages = Setting::get('time_between_messages');
	
	if (AccessLevel::check_access($sender, "rl")) {
		return 0;
	}

	$current_time = time();
	if (!isset($shopping_spam_protection[$sender])) {
		$shopping_spam_protection[$sender] = 0;
	}
	$last_time_msg_sent = $shopping_spam_protection[$sender];

	if (($current_time - $last_time_msg_sent) < $time_between_messages) {
		return $time_between_messages - ($current_time - $last_time_msg_sent);
	}

	return 0;
}

function process_spam_request($sender, $message, $channel, $side) {
	global $chatBot;

	$current_time = time();
	$timeleft = time_left_for_spam_protection($sender);

	if ($timeleft > 0) {
		$chatBot->send("You may not send a message for another " . round($timeleft/60) . " minutes.", $sender);
	} else {
		global $shopping_spam_protection;
		$shopping_spam_protection[$sender] = $current_time;

		spam_shopping_message($message, $channel, $side);
	}
}

?>
