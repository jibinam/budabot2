<?php

if ($packet_type == AOCP_GROUP_MESSAGE && $sender != $chatBot->vars['name']) {
	$b = unpack("C*", $args[0]);
	// check to make sure message is from a shopping channel
	// (first byte = 134; see http://aodevs.com/forums/index.php/topic,42.msg2192.html#msg2192)
	if ($b[1] == 134) {
		$channel = $chatBot->get_gname($args[0]);
		$sender	= $chatBot->lookup_user($args[1]);
		$message = $args[2];
	
		$blocked = false;
		$db->query("SELECT regex FROM filter_content");
		$data = $db->fObject('all');
		forEach ($data as $row) {
			if (preg_match("/$row->regex/i", $message)) {
				Logger::log('INFO', 'ShoppingSpam', "BLOCKED -- $message");
				return;
			}
		}

		global $lastMessage;
		if ($lastMessage != $message) {
			$newChannel = str_replace(" shopping 11-50", "", $channel);  // shorten channel name (e.g. remove "shopping " from "OT shopping 11-50" to get "OT")

			$senderLink = Text::make_userlink($sender);
			$chatBot->sendPrivate("[$newChannel] $senderLink: $message", $this->settings['shopbot_master']);
			$lastMessage = $message;
		} else {
			//echo "DUPLICATE-$message\n";
		}
	}
}

?>
