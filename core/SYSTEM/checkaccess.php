<?php

$accessLevel = Registry::getInstance('accessLevel');

if (preg_match("/^checkaccess$/i", $message) || preg_match("/^checkaccess (.+)$/i", $message, $arr)) {
	if (isset($arr)) {
		$name = ucfirst(strtolower($arr[1]));
	} else {
		$name = $sender;
	}
	
	$accessLevel = $accessLevel->getDisplayName($accessLevel->getAccessLevelForCharacter($name));
	
	$msg = "Access level for $name is <highlight>$accessLevel<end>.";
	$sendto->reply($msg);
} else {
	$syntax_error = true;
}

?>
