<?php

if (preg_match("/^news$/i", $message, $arr)) {
	$db->query("SELECT * FROM news ORDER BY `time` DESC LIMIT 0, 10");
	$data = $db->fObject('all');
	if ($db->numrows() != 0) {
		$link = "<header>::::: News :::::<end>\n\n";
		forEach ($data as $row) {
		  	if (!$updated) {
				$updated = $row->time;
			}
			
		  	$link .= "<highlight>Date:<end> ".gmdate("dS M, H:i", $row->time)."\n";
		  	$link .= "<highlight>Author:<end> $row->name\n";
		  	$link .= "<highlight>Options:<end> ".Text::make_link("Delete this news entry", "/tell <myname> remnews $row->id", "chatcmd")."\n";
		  	$link .= "<highlight>Message:<end> $row->news\n\n";
		}
		$msg = Text::make_link("News", $link)." [Last updated at ".gmdate("dS M, H:i", $updated)."]";
	} else {
		$msg = "No News recorded yet.";
	}
		
    $chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>