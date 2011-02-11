<?php

if (preg_match("/^lc$/i", $message, $arr)) {
	$sql = "SELECT * FROM playfields WHERE `id` IN (SELECT DISTINCT `playfield_id` FROM tower_site) ORDER BY `short_name`";
	$db->query($sql);
	
	$blob = "Land Control Index\n\n";
	while (($row = $db->fObject()) != false) {
		$baseLink = Text::make_link($row->long_name, "/tell <myname> lc $row->short_name", 'chatcmd');
		$blob .= "$baseLink <highlight>($row->short_name)<end>\n";
	}
	$msg = Text::make_link('Land Control Index', $blob, 'blob');
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
		JOIN playfields p ON (t1.playfield_id = p.id)
		WHERE t1.playfield_id = $playfield->id";

	$db->query($sql);
	$numrows = $db->numrows();
	$blob = "All bases in $playfield->long_name\n\n";
	while (($row = $db->fObject()) != false) {
		$gas_level = getGasLevel($row->close_time);
		$blob .= formatSiteInfo($row) . "\n\n";
	}
	
	$msg = Text::make_link("All Bases in $playfield->long_name", $blob, 'blob');
	
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
		JOIN playfields p ON (t1.playfield_id = p.id)
		WHERE t1.playfield_id = $playfield->id AND t1.site_number = $site_number";

	$db->query($sql);
	$numrows = $db->numrows();
	$blob = "$playfield->short_name $site_number\n\n";
	while (($row = $db->fObject()) != false) {
		$gas_level = getGasLevel($row->close_time);
		$blob .= formatSiteInfo($row) . "\n\n";
	}
	
	if ($numrows > 0) {
		$msg = Text::make_link("$playfield->short_name $site_number", $blob, 'blob');
	} else {
		$msg = "Invalid site number.";
	}
	
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>