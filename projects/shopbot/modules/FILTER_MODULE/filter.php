<?php

if (preg_match("/^filter add content (.*)$/i", $message, $arr)) {
	$regex = $arr[1];
	
	$db->exec("INSERT INTO filter_content (addedBy, regex) VALUES (?, ?);", $sender, $regex);
	$msg = "Content filter added successfully.";
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>
