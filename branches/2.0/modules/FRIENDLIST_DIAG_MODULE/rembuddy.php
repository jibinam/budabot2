<?php

if (preg_match("/^rembuddy (.+) (.+)$/i", $message, $arr)) {
	$name = $arr[1];
	$type = $arr[2];
	$uid = $chatBot->get_uid($name);
	
	if (Buddylist::remove($uid, $type)) {
		$msg = "$name removed from the buddy list successfully.";
	} else {
		$msg = "Could not remove $name from the buddy list.";
	}
	
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>