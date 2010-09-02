<?php

if (preg_match("/^uptime$/i", $message, $arr)) {
	$datediff = Util::date_difference($chatBot->startup, time());
	$msg = "The bot has been online for $datediff.";
	$chatBot->send($msg, $sendto);
}

?>