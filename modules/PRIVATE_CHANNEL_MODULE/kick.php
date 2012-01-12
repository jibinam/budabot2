<?php

if (preg_match("/^kick (.+)$/i", $message, $arr)) {
    $uid = $chatBot->get_uid($arr[1]);
    $name = ucfirst(strtolower($arr[1]));
    if ($uid) {
        if (isset($chatBot->chatlist[$name])) {
			$msg = "<highlight>$name<end> has been kicked from the private channel.";
		} else {
			$msg = "<highlight>$name<end> is not in the private channel.";
		}

		// we kick whether they are in the channel or not in case the channel list is bugged
		$chatBot->privategroup_kick($name);
    } else {
		$msg = "Character <highlight>{$name}<end> does not exist.";
	}
	
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}
?>