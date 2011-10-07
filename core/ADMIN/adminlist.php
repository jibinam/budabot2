<?php

if (preg_match("/^adminlist$/i", $message) || preg_match("/^admins$/i", $message)) {
	$list = "<header>::::: Adminlist :::::<end>\n\n";

	$list .= "<highlight>Administrators<end>\n";	
	forEach ($chatBot->admins as $who => $data) {
		if ($chatBot->admins[$who]["level"] == 4) {
			if ($who != "") {
				$list.= "<tab>$who ";
				
				if (AccessLevel::check_access($who, 'superadmin')) {
					$list .= "(<orange>Super Administrator<end>) ";
				}
					
				if (Buddylist::is_online($who) == 1 && isset($chatBot->chatlist[$who])) {
					$list.="(<green>Online and in chat<end>)";
				} else if (Buddylist::is_online($who) == 1) {
					$list.="(<green>Online<end>)";
				} else {
					$list.="(<red>Offline<end>)";
				}
					
				$list.= "\n";
			}
		}
	}

	$list .= "<highlight>Moderators<end>\n";
	forEach ($chatBot->admins as $who => $data){
		if ($chatBot->admins[$who]["level"] == 3){
			if ($who != "") {
				$list.= "<tab>$who ";
				if (Buddylist::is_online($who) == 1 && isset($chatBot->chatlist[$who])) {
					$list.="(<green>Online and in chat<end>)";
				} else if (Buddylist::is_online($who) == 1) {
					$list.="(<green>Online<end>)";
				} else {
					$list.="(<red>Offline<end>)";
				}
				$list.= "\n";
			}
		}
	}

	$list .= "<highlight>Raidleaders<end>\n";	
	forEach ($chatBot->admins as $who => $data){
		if ($chatBot->admins[$who]["level"] == 2){
			if ($who != "") {
				$list.= "<tab>$who ";
				if (Buddylist::is_online($who) == 1 && isset($chatBot->chatlist[$who])) {
					$list.="(<green>Online and in chat<end>)";
				} else if (Buddylist::is_online($who) == 1) {
					$list.="(<green>Online<end>)";
				} else {
					$list.="(<red>Offline<end>)";
				}
				$list.= "\n";
			}
		}
	}

	$link = Text::make_blob('Bot Administrators', $list);	
	$chatBot->send($link, $sendto);
} else {
	$syntax_error = true;
}

?>