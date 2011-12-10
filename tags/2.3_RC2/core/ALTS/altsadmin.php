<?php

if (preg_match("/^altsadmin add (.+) (.+)$/i", $message, $names)) {
	if ($names[1] == '' || $names[2] == '') {
		$syntax_error = true;
		return;
	}

	$name_main = ucfirst(strtolower($names[1]));
	$name_alt = ucfirst(strtolower($names[2]));
	$uid_main = $chatBot->get_uid($name_main);
	$uid_alt = $chatBot->get_uid($name_alt);

	if (!$uid_alt) {
		$msg = "Player <highlight>$name_alt<end> does not exist.";
		$chatBot->send($msg, $sendto);
		return;
	}
	if (!$uid_main) {
		$msg = " Player <highlight>$name_main<end> does not exist.";
		$chatBot->send($msg, $sendto);
		return;
	}

	$mainInfo = Alts::get_alt_info($name_main);
	$altinfo = Alts::get_alt_info($name_alt);
	if ($altinfo->main == $mainInfo->main) {
		$msg = "Player <highlight>$name_alt<end> is already registered as an alt of <highlight>{$altinfo->main}<end>.";
		$chatBot->send($msg, $sendto);
		return;
	}
	
	if (count($altInfo->alts) > 0) {
		// Already registered to someone else
		if ($altInfo->main == $name) {
			$msg = "$name is already registered as a main with alts.";
		} else {
			$msg = "$name is already registered as an of alt of {$altInfo->main}.";
		}
		$chatBot->send($msg, $sendto);
		return;
	}

	Alts::add_alt($mainInfo->main, $name_alt, 1);
	$msg = "<highlight>$name_alt<end> has been registered as an alt of {$mainInfo->main}.";
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^altsadmin rem (.+) (.+)$/i", $message, $names)) {
	if ($names[1] == '' || $names[2] == '') {
		$syntax_error = true;
		return;
	}

	$name_main = ucfirst(strtolower($names[1]));
	$name_alt = ucfirst(strtolower($names[2]));

	if (Alts::rem_alt($name_main, $name_alt) == 0) {
		$msg = "Player <highlight>$name_alt<end> not listed as an alt of Player <highlight>$name_main<end>.  Please check the player's !alts listings.";
	} else {
		$msg = "<highlight>$name_alt<end> has been deleted from the alt list of <highlight>$name_main.<end>";
	}
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^altsadmin export (.+)$/i", $message, $arr)) {
	/* the file may only be stored under the current directory */
	$file_name = "./".basename($arr[1]);
	/* do not overwrite existing files */
	if (file_exists($file_name)) {
		$msg = "<highlight>File already exists, please specify another one.<end>";
		$chatBot->send($msg, $sendto);
		return;
	}

	/* get the complete alts list */
	$db->query("SELECT * FROM alts");
	$alts_table = $db->fObject("all");

	/* write it to a file */
	$file = fopen($file_name, 'w');
	fwrite($file, "alt main\n");
	forEach ($alts_table as $row) {
		fwrite($file, $row->alt.' '.$row->main."\n");
	}
	fclose($file);

	$msg = "Export completed into '$file_name'";
	$chatBot->send($msg, $sendto);
	return;
} else if (preg_match("/^altsadmin import (.+)/i", $message, $arr)) {
	/* the file may only be stored under the current directory */
	$file_name = "./".basename($arr[1]);
	/* check for existing file */
	if (!file_exists($file_name)) {
		$msg = "<highlight>File '$file_name' does not exist.<end>";
		$chatBot->send($msg, $sendto);
		return;
	}

	/* open the file */
	$file = fopen($file_name, 'r');

	/* get first line and check for "alt main" */
	$firstline = fgets($file);
	if (stripos($firstline, "alt main") === false) {
		$msg = "File didn't start with expected 'alt main', aborting import.";
		$chatBot->send($msg, $sendto);
		return;
	}
	
	$altcounter = 0;
	while (!feof($file)) {
		$line = fgets($file);
		$explodeline = explode(' ', $line);
		$name_alt = $explodeline[0];
		$name_main = $explodeline[1];
		$db->exec("INSERT INTO alts (`alt`, `main`) VALUES ('$name_alt', '$name_main')");
		++$altcounter;
	}
	$msg = "Succesfully added $altcounter entries into the alts table.";
	$chatBot->send($msg, $sendto);
	return;
} else {
	$syntax_error = true;
}

?>