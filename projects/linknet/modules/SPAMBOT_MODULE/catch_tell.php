<?php

if ($sender == $this->settings["masterrelay"] && preg_match("/^spamrelay (.+)$/i", $message, $arr)) {
	$delimiter = " ";
	list($spamtype, $spammessage) = explode($delimiter, $arr[1], 2);

	$slavename = $this->vars["name"];
	$letters = "Linknet";
	$slavename = str_ireplace($letters, "", $slavename);

	$slaveid = (int)$slavename;

	send_spam_to_members($slaveid, $spamtype, $spammessage);

	echo "Message Sent To Online Members. \n";
	$stop_execution = true;
}

?>