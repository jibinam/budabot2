<?php

if (preg_match("/^updateitems$/i", $message)) {
	// get list of files in ITEMS_MODULE
	$data = file_get_contents("http://budabot2.googlecode.com/svn/trunk/modules/ITEMS_MODULE");
	$data = str_replace("<hr noshade>", "", $data);  // not valid xml

	$xml = new SimpleXmlElement($data);

	// find the latest items db version on the server
	$latestVersion = null;
	forEach ($xml->body->ul->li as $item) {
		if (preg_match("/^aodb(.*)\\.sql$/i", $item->a, $arr)) {
			if ($latestVersion === null) {
				$latestVersion = $arr[1];
			} else if (Util::compare_version_numbers($arr[1], $currentVersion)) {
				$latestVersion = $arr[1];
			}
		}
	}

	if ($latestVersion !== null) {
		$currentVersion = Setting::get("aodb_db_version");
		
		// if server version is greater than current version, download and load server version
		if ($currentVersion === false || Util::compare_version_numbers($latestVersion, $currentVersion) > 0) {
			// download server version and save to ITEMS_MODULE directory
			$contents = file_get_contents("http://budabot2.googlecode.com/svn/trunk/modules/ITEMS_MODULE/aodb{$latestVersion}.sql");
			$fh = fopen("./modules/ITEMS_MODULE/aodb{$latestVersion}.sql", 'w');
			fwrite($fh, $contents);
			fclose($fh);
			
			// load the sql file into the db
			DB::loadSQLFile("ITEMS_MODULE", "aodb");
			
			$chatBot->send("The items database has been updated to the latest version.  Version: $latestVersion", $sendto);
		} else {
			$chatBot->send("The items database is already up to date.  Version: $currentVersion", $sendto);
		}
	} else {
		$chatBot->send("There was a problem finding the latest version on the server", $sendto);
	}
} else {
	$syntax_error = true;
}

?>