<?php

if (preg_match("/^spam (shopping|ooc) (clan|omni|neut|all|both) (.+)$/i", $message, $arr)) {
	$channel = strtolower($arr[1]);
	$side = strtolower($arr[2]);
	$replyTo = $sender;
	$message = $arr[3];

	$link = "<a $style href='user://$replyTo'>Send $replyTo a tell</a>";
	$msg = "$message -> $link";

	process_spam_request($sender, $msg, $channel, $side);
} else if (preg_match("/^spamproxy (shopping|ooc) (clan|omni|neut|all|both) ([a-z0-9-]+) (.+)$/i", $message, $arr)) {
	$channel = strtolower($arr[1]);
	$side = strtolower($arr[2]);
	$replyTo = ucfirst(strtolower($arr[3]));
	$message = $arr[4];

	$link = "<a $style href='user://$replyTo'>Send $replyTo a tell</a>";
	$msg = "$message -> $link";

	process_spam_request($sender, $msg, $channel, $side);
} else {
	$syntax_error = true;
}

?>