<?php

if (preg_match("/^addbuddy (.+) (.+)$/i", $message, $arr)) {
	$name = $arr[1];
	$type = $arr[2];
	
	if ($buddyList->add($name, $type)) {
		$msg = "$name added to the buddy list successfully.";
	} else {
		$msg = "Could not add $name to the buddy list.";
	}
	
	$sendto->reply($msg);
} else {
	$syntax_error = true;
}

?>