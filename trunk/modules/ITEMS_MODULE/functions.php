<?php

function download_newest_itemsdb() {
	global $chatBot;
	$db = $chatBot->getInstance('db');

	Logger::log('INFO', 'ITEMS_MODULE', "Starting items db update");

	// get list of files in ITEMS_MODULE
	$data = file_get_contents("http://budabot2.googlecode.com/svn/trunk/modules/ITEMS_MODULE");
	$data = str_replace("<hr noshade>", "", $data);  // not valid xml

	try {
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
	} catch (Exception $e) {
		Logger::log('ERROR', 'ITEMS_MODULE', "Error updating items db: " . $e->getMessage());
		return "Error updating items db: " . $e->getMessage();
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
			
			$db->begin_transaction();
			
			// load the sql file into the db
			$db->loadSQLFile("ITEMS_MODULE", "aodb");
			
			$db->commit();
			
			Logger::log('INFO', 'ITEMS_MODULE', "Items db updated from '$currentVersion' to '$latestVersion'");
			$msg = "The items database has been updated to the latest version.  Version: $latestVersion";
		} else {
			Logger::log('INFO', 'ITEMS_MODULE', "Items db already up to date '$currentVersion'");
			$msg = "The items database is already up to date.  Version: $currentVersion";
		}
	} else {
		Logger::log('ERROR', 'ITEMS_MODULE', "Could not find latest items db on server");
		$msg = "There was a problem finding the latest version on the server";
	}
	
	Logger::log('INFO', 'ITEMS_MODULE', "Finished items db update");
	
	return $msg;
}

function find_items_from_local($search, $ql) {
	global $chatBot;
	$db = $chatBot->getInstance('db');

	$tmp = explode(" ", $search);
	$first = true;
	forEach ($tmp as $key => $value) {
		$value = str_replace("'", "''", $value);
		if ($first) {
			$query .= "`name` LIKE '%$value%'";
			$first = false;
		} else {
			$query .= " AND `name` LIKE '%$value%'";
		}
	}

	if ($ql) {
		$query .= " AND `lowql` <= $ql AND `highql` >= $ql";
	}

	$sql = "SELECT * FROM aodb WHERE $query ORDER BY `name` ASC, highql DESC LIMIT 0, " . Setting::get("maxitems");
	$data = $db->query($sql);
	$num = count($data);
	if ($num == 0) {
		if ($ql) {
			$msg = "No items found matching <highlight>$search<end> with QL <highlight>$ql<end>.";
		} else {
			$msg = "No items found matching <highlight>$search<end>.";
		}
		return $msg;
	} else if ($num > 3) {
		$blob = "<header> :::::: Item Search Results (" . Setting::get('aodb_db_version') . ") :::::: <end>\n\n";
		$blob .= formatSearchResults($data, $ql, true);
		$blob .= "\n\n<highlight>Item DB rips provied by MajorOutage (RK1)<end>";
		$link = Text::make_blob("$num results in total", $blob);

		return $link;
	} else {
		return trim(formatSearchResults($data, $ql, false));
	}
}

function formatSearchResults($data, $ql, $showImages) {
	$list = '';
	forEach ($data as $row) {
		if ($showImages) {
			$list .= "<img src='rdb://".$row->icon."'> \n";
		}
		if ($ql) {
			$list .= "QL $ql ".Text::make_item($row->lowid, $row->highid, $ql, $row->name);
		} else {
			$list .= Text::make_item($row->lowid, $row->highid, $row->highql, $row->name);		  
		}
		if ($row->lowql != $row->highql) {
			$list .= " (QL".$row->lowql." - ".$row->highql.")\n";
		} else {
			$list .= " (QL".$row->lowql.")\n";
		}
		if ($showImages) {
			$list .= "\n";
		}
	}
	return $list;
}

?>