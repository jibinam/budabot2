<?php

if (preg_match("/^uptime$/i", $message, $arr)) {
	$datediff = Util::unixtime_to_readable(time() - $chatBot->startup);
	$msg = "The bot has been online for $datediff.";
	$chatBot->send($msg, $sendto);
}

?>