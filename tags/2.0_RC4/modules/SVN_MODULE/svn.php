<?php

if (preg_match("/^svn update/i", $message)) {
	$command = "svn update --accept " . $this->settings['svnconflict'];
	$output = array();
	$return_var = '';
	exec($command, $output, $return_var);
	
	$window = "::: SVN UPDATE output :::\n\n";
	$window .= $command . "\n\n";
	forEach ($output as $line) {
		$window .= $line . "\n";
	}
	
	$msg = Text::make_link('svn update output', $window);
	
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^svn info/i", $message)) {
	$command = "svn info";
	$output = array();
	$return_var = '';
	exec($command, $output, $return_var);
	
	$window = "::: SVN INFO output :::\n\n";
	$window .= $command . "\n\n";
	forEach ($output as $line) {
		$window .= $line . "\n";
	}
	
	$msg = Text::make_link('svn info output', $window);
	
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>