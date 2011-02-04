<?php

if (preg_match("/^links$/i", $message)) {
	$blob = "<header> :::::: Links :::::: <end>\n\n";

	$sql = "SELECT * FROM links ORDER BY dt DESC";
  	$db->query($sql);
	$data = $db->fObject('all');
  	forEach ($data as $row) {
	  	$remove = bot::makeLink('Remove', "/tell <myname> <symbol>links rem $row->id" , 'chatcmd');
		$website = bot::makeLink($row->website, "/start $row->website", 'chatcmd');
		$dt = gmdate("M j, Y, G:i", $row->dt);
	  	$blob .= "$website <white>$row->comments<end> [<green>$row->name<end>] <white>$dt<end> $remove\n";
	}
	
	if (count($data) == 0) {
		$msg = "No links found.";
	} else {
		$msg = bot::makeLink('Links', $blob, 'blob');
	}
  	
	bot::send($msg, $sendto);
} else if (preg_match("/^links add ([^ ]+) (.+)$/i", $message, $arr)) {
	$website = str_replace("'", "''", $arr[1]);
	$comments = str_replace("'", "''", $arr[2]);

	$db->query("INSERT INTO links (`name`, `website`, `comments`, `dt`) VALUES('$sender', '$website', '$comments', '" . time() . "')");
	$msg = "Link added successfully.";
    bot::send($msg, $sendto);
} else if (preg_match("/^links rem ([0-9]+)$/i", $message, $arr)) {
	$id = $arr[1];

	$numRows = $db->exec("DELETE FROM links WHERE id = $id AND name LIKE '$sender'");
	if ($numRows) {
		$msg = "Link deleted successfully.";
	} else {
		$msg = "Link could not be found or was not submitted by you.";
	}
    bot::send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>
