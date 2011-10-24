<?php
   /*
   ** Author: Legendadv (RK2)
   ** IRC RELAY MODULE
   **
   ** Developed for: Budabot(http://aodevs.com/index.php/topic,512.0.html)
   **
   */
   
if (IRC::isConnectionActive()) {
	$whois = Player::get_by_name($sender);
	if ($whois === null) {
		$whois = new stdClass;
	}
	if ($whois->guild == "") {
		$whois->guild = "Not in a guild";
	}
	$msg = "$sender ({$whois->level}/{$whois->ai_level}, {$whois->profession}, {$whois->guild})";
	
	if ($type == "joinPriv") {
		$msg .= " has joined the private channel.";
	} else {
		$msg .= " has logged on.";
	}

	// Alternative Characters Part
	$main = false;
	// Check if $sender is hisself the main
	$db->query("SELECT * FROM alts WHERE `main` = '$sender'");
	if ($db->numrows() == 0){
		// Check if $sender is an alt
		$db->query("SELECT * FROM alts WHERE `alt` = '$sender'");
		if ($db->numrows() != 0) {
			$row = $db->fObject();
			$main = $row->main;
		}
	} else {
		$main = $sender;
	}

	if ($main != $sender && $main != false) {
		$msg .= " Main: $main";
	} else if ($main != false) {
		$msg .= " Alt of $main";
	}


	if (($row->logon_msg != '') && ($row->logon_msg != '0')) {
		$msg .= " - " . $row->logon_msg;
	}
	
	if ($type == "joinPriv") {
		IRC::send(encodeGuildMessage($chatBot->vars['my_guild'], $msg));
		if (Setting::get('irc_debug_messages') == 1) {
			Logger::log_chat("Out. IRC Msg.", -1, "$sender has joined the private chat");
		}
	} else if ($type == "logOn" && isset($chatBot->guildmembers[$sender])) {
		if (Setting::get('first_and_last_alt_only') == 1) {
			// if at least one alt/main is still online, don't show logoff message
			$altInfo = Alts::get_alt_info($sender);
			if (count($altInfo->get_online_alts()) > 1) {
				return;
			}
		}

		IRC::send(encodeGuildMessage($chatBot->vars['my_guild'], $msg));
		if (Setting::get('irc_debug_messages') == 1) {
			Logger::log_chat("Out. IRC Msg.", -1, "$sender has logged on");
		}
	}
}

?>