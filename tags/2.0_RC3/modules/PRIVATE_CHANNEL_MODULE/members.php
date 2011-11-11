<?php

if (preg_match("/^members$/i", $message)) {
	$db->query("SELECT * FROM members_<myname> ORDER BY `name`");
	$autoguests = $db->numrows();
	if ($autoguests != 0) {
	  	$list .= "<header>::::: Members :::::<end>\n\n";
	  	while ($row = $db->fObject()) {
	  	  	if ($this->buddy_online($row->name)) {
				$status = "<green>Online";
				if (isset($this->chatlist[$sender])) {
			    	$status .= " and in channel";
				}
			} else {
				$status = "<red>Offline";
			}

	  		$list .= "<tab>- $row->name ($status<end>)\n";
	  	}
	  	
	    $msg = bot::makeLink("$autoguests member(s)", $list);
		bot::send($msg, $sendto);
	} else {
       	bot::send("There are no members of this bot.", $sendto);
	}
} else {
	$syntax_error = true;
}

?>