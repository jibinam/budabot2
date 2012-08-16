<?php

/*
	Inits Module
	Author: William "Xyphos" Scott
	Jan 19, 2010
*/

/// inits <a href="itemref://280727/280727/300">Sloth of the Xan</a>
if (preg_match('/^inits \<a href\=\"itemref\:\/\/([0-9]+)\/([0-9]+)\/([0-9]+)\"\>/i', $message, $arr)) {
	$url = "http://inits.xyphos.com/?";
	$url .= "lowid={$arr[1]}&";
	$url .= "highid={$arr[2]}&";
	$url .= "ql={$arr[3]}&";
	$url .= "output=aoml";

	$msg = "Calculating Inits... Please wait.";
	$sendto->reply($msg);

	$msg = file_get_contents($url, 0);
	if (empty($msg)) {
		$msg = "Unable to query Central Items Database.";
	}
	$sendto->reply($msg);
} else {
	$syntax_error = true;
}

?>