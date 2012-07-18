<?php

if (preg_match("/^members$/i", $message)) {
	$data = $db->query("SELECT * FROM members_<myname> ORDER BY `name`");
	$autoguests = count($data);
	if ($autoguests != 0) {
	  	$list = '';
		forEach ($data as $row) {
			$online = $buddylistManager->is_online($row->name);
	  	  	if (isset($chatBot->chatlist[$row->name])) {
				$status = "(<green>Online and in channel<end>)";
			} else if ($online === 1) {
				$status = "(<green>Online<end>)";
			} else if ($online === 0) {
				$status = "(<red>Offline<end>)";
			} else {
				$status = "(<orange>Unknown<end>)";
			}

	  		$list .= "<tab>- $row->name {$status}\n";
	  	}
	  	
	    $msg = Text::make_blob("Members ($autoguests)", $list);
		$sendto->reply($msg);
	} else {
       	$sendto->reply("There are no members of this bot.");
	}
} else {
	$syntax_error = true;
}

?>