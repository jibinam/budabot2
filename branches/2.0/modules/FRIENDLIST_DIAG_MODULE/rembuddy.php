<?php

if (preg_match("/^rembuddy (.+) (.+)$/i", $message, $arr)) {
	$buddy = Player::create($arr[1]);
	$type = $arr[2];
	
	if ($buddy == null) {
		$msg = "<highlight>{$arr[1]}<end> does not exist.";
		$chatBot->send($msg, $sendto);
		return;
	}
	
	if ($buddy->remove_from_buddylist($type)) {
		$msg = "{$buddy->name} removed from the buddy list successfully.";
	} else {
		$msg = "Could not remove {$buddy->name} from the buddy list.";
	}
	
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>