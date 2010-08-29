<?php

if (preg_match("/^svn update/i", $message)) {
	$command = "svn update --accept " . Settings::get('svnconflict');
	$output = array();
	$return_var = '';
	exec($command, $output, $return_var);
	
	$window .= $command . "\n\n";
	forEach ($output as $line) {
		$window .= $line . "\n";
	}
	
	$msg = Text::makeBlob('SVN Update output', $window);
	
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^svn info/i", $message)) {
	$command = "svn info";
	$output = array();
	$return_var = '';
	exec($command, $output, $return_var);
	
	$window .= $command . "\n\n";
	forEach ($output as $line) {
		$window .= $line . "\n";
	}
	
	$msg = Text::makeBlob('SVN Info output', $window);
	
	$chatBot->send($msg, $sendto);
} else {
	$syntaxt_error = true;
}

?>