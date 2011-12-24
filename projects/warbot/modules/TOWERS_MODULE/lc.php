<?php

if (preg_match("/^lc$/i", $message, $arr)) {
	$sql = "SELECT * FROM playfields WHERE `id` IN (SELECT DISTINCT `playfield_id` FROM tower_site) ORDER BY `short_name`";
	$data = $db->query($sql);
	
	$blob = "Land Control Index\n\n";
	forEach ($data as $row) {
		$baseLink = Text::make_chatcmd($row->long_name, "/tell <myname> lc $row->short_name");
		$blob .= "$baseLink <highlight>($row->short_name)<end>\n";
	}
	$msg = Text::make_blob('Land Control Index', $blob);
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^lc ([0-9a-z]+)$/i", $message, $arr)) {
	$playfield_name = strtoupper($arr[1]);
	$playfield = Playfields::get_playfield_by_name($playfield_name);
	if ($playfield === null) {
		$msg = "Playfield '$playfield_name' could not be found";
		$chatBot->send($msg, $sendto);
		return;
	}

	$sql = "SELECT * FROM tower_site t1
		LEFT JOIN scout_info s ON (t1.playfield_id = s.playfield_id AND t1.site_number = s.site_number)
		LEFT JOIN tower_info t2 ON (t1.playfield_id = t2.playfield_id AND t1.site_number = t2.site_number)
		JOIN playfields p ON (t1.playfield_id = p.id)
		WHERE t1.playfield_id = $playfield->id";
		
		echo $sql . "\n";

	$data = $db->query($sql);
	$blob = "All bases in $playfield->long_name\n\n";
	forEach ($data as $row) {
		$gas_level = getGasLevel($row->close_time);
		$blob .= formatSiteInfo($row) . "\n\n";
	}
	
	$msg = Text::make_link("All Bases in $playfield->long_name", $blob);
	
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^lc ([0-9a-z]+) ([0-9]+)$/i", $message, $arr)) {
	$playfield_name = strtoupper($arr[1]);
	$playfield = Playfields::get_playfield_by_name($playfield_name);
	if ($playfield === null) {
		$msg = "Playfield '$playfield_name' could not be found";
		$chatBot->send($msg, $sendto);
		return;
	}

	$site_number = $arr[2];
	$sql = "SELECT * FROM tower_site t1
		LEFT JOIN scout_info s ON (t1.playfield_id = s.playfield_id AND t1.site_number = s.site_number)
		LEFT JOIN tower_info t2 ON (t1.playfield_id = t2.playfield_id AND t1.site_number = t2.site_number)
		JOIN playfields p ON (t1.playfield_id = p.id)
		WHERE t1.playfield_id = $playfield->id AND t1.site_number = $site_number";

	$data = $db->query($sql);
	$numrows = count($data);
	$blob = "$playfield->short_name $site_number\n\n";
	forEach ($data as $row) {
		$gas_level = getGasLevel($row->close_time);
		$blob .= formatSiteInfo($row) . "\n\n";
	}
	
	if ($numrows > 0) {
		$msg = Text::make_blob("$playfield->short_name $site_number", $blob);
	} else {
		$msg = "Invalid site number.";
	}
	
	$chatBot->send($msg, $sendto);
} else if (preg_match("/^lc org (.+)$/i", $message, $arr)) {
	$org = $arr[1];
	
	$org = str_replace("'", "''", $org);
	$sql = "SELECT * FROM tower_site t1
		LEFT JOIN scout_info s ON (t1.playfield_id = s.playfield_id AND t1.site_number = s.site_number)
		LEFT JOIN tower_info t2 ON (t1.playfield_id = t2.playfield_id AND t1.site_number = t2.site_number)
		JOIN playfields p ON (t1.playfield_id = p.id)
		WHERE s.org_name LIKE '$org'";

	$data = $db->query($sql);
	$numrows = count($data);
	forEach ($data as $row) {
		$gas_level = getGasLevel($row->close_time);
		$blob .= formatSiteInfo($row) . "\n\n";
	}
	
	if ($numrows > 0) {
		$msg = Text::make_blob("Bases belonging to $org", $blob);
	} else {
		$msg = "Could not find any sites for org '$org'";
	}
	
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>