<?php

if (preg_match("/^svn update/i", $message)) {
	$command = "svn update --accept " . Settings::get('svnconflict');
	$output = array();
	$return_var = '';
	exec($command, $output, $return_var);
	
	$window = "::: SVN UPDATE output :::\n\n";
	$window .= $command . "\n\n";
	forEach ($output as $line) {
		$window .= $line . "\n";
	}
	
	$msg = Links::makeLink('svn update output', $window);
	
	$this->send($msg, $sendto);
} else if (preg_match("/^svn info/i", $message)) {
	$command = "svn info";
	$output = array();
	$return_var = '';
	exec($command, $output, $return_var);
	
	$window = "::: SVN INFO output :::\n\n";
	forEach ($output as $line) {
		$window .= $line . "\n";
	}
	
	$msg = Links::makeLink('svn info output', $window);
	
	$this->send($msg, $sendto);
} else {
	$syntaxt_error = true;
}

?>