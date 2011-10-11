<?php
   /*
   ** Description: Shows the History
   ** Version: 0.1
   */

if (preg_match("/^history$/i", $message)) {

   	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if ($db->numrows() == 0) {	
		bot::send("You are not registered with this bot.", $sender);
		return;
	}

	$whois = Player::get_by_name($sender);

	if ($whois->level < 100) {	
		bot::send("You must be at least level 100 to view history.", $sender);
		return;
	}

	$db->query("SELECT * FROM spamhistory ORDER BY `settime` DESC LIMIT 0, 20");
	if ($db->numrows() != 0) {
		$list = "<header>::::: History about the last 20 messages :::::<end>\n\n";
		while ($row = $db->fObject()) {
			$list .= "<white>Message: <green>$row->message<end>\n";
			$list .= "<white>Type: <green>$row->type<end>\n";
			$list .= "<white>Sender: <green>$row->name<end>\n";
			$list .= "<white>Time: <green>".gmdate("l F d, Y - H:i", $row->settime)."(GMT)\n";	
			$list .= "\n";
        }
	    $msg = bot::makeLink("Message History", $list);
	} else {
		$msg = "No History.";
	}

	bot::send($msg, $sender);

} else {
	$syntax_error = true;
}

?>

