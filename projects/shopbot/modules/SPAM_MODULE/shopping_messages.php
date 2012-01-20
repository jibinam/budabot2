<?php

if ($packet_type == AOCP_GROUP_MESSAGE && $sender != $chatBot->vars['name']) {
	$b = unpack("C*", $args[0]);
	// check to make sure message is from a shopping channel
	// (first byte = 134; see http://aodevs.com/forums/index.php/topic,42.msg2192.html#msg2192)
	if ($b[1] == 134) {
		$channel = $chatBot->get_gname($args[0]);
		$sender	= $chatBot->lookup_user($args[1]);
		$message = $args[2];
		
		if (Ban::is_banned($sender)) {
			return;
		}
	
		$blocked = false;
		$data = $db->query("SELECT regex FROM filter_content");
		forEach ($data as $row) {
			if (preg_match("/$row->regex/i", $message)) {
				Logger::log('INFO', 'ShoppingSpam', "BLOCKED -- $message");
				return;
			}
		}
		
		if (Setting::get('add_ql_info') == 1) {
			$pattern = '/<a href="itemref:\/\/(\d+)\/(\d+)\/(\d+)">([^<]+)<\/a>/';
			$message = preg_replace($pattern, "<a href=\"itemref://\\1/\\2/\\3\">\\4 (QL \\3)</a>", $message);
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
