<?php

if (preg_match("/^memory$/i", $message, $arr)) {
	$blob = "<header>::::: Adminlist :::::<end>\n\n";
	$blob .= "Current Memory Usage: " . memory_get_usage() . "\n";
	$blob .= "Current Memory Usage (Real): " . memory_get_usage(1) . "\n";
	$blob .= "Peak Memory Usage: " . memory_get_usage() . "\n";
	$blob .= "Peak Memory Usage (Real): " . memory_get_peak_usage(1) . "\n";
	$msg = Text::makeLink('Adminlist', $blob);	
	$this->send($msg, $sendto);
}

?>