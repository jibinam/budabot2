<?php

$playfields = Registry::getInstance('playfields');

if (preg_match("/^lc$/i", $message, $arr)) {
	$sql = "SELECT * FROM playfields WHERE `id` IN (SELECT DISTINCT `playfield_id` FROM tower_site) ORDER BY `short_name`";
	$data = $db->query($sql);

	$blob = '';
	forEach (data as $row) {
		$baseLink = Text::make_chatcmd($row->long_name, "/tell <myname> lc $row->short_name");
		$blob .= "$baseLink <highlight>($row->short_name)<end>\n";
	}
	$msg = Text::make_blob('Land Control Index', $blob);
	$sendto->reply($msg);
} else if (preg_match("/^lc ([0-9a-z]+)$/i", $message, $arr)) {
	$playfield_name = strtoupper($arr[1]);
	$playfield = $playfields->get_playfield_by_name($playfield_name);
	if ($playfield === null) {
		$msg = "Playfield '$playfield_name' could not be found";
		$sendto->reply($msg);
		return;
	}

	$sql = "SELECT * FROM tower_site t1
		JOIN playfields p ON (t1.playfield_id = p.id)
		WHERE t1.playfield_id = ?";

	$data = $db->query($sql, $playfield->id);
	$blob = '';
	forEach ($data as $row) {
		$gas_level = getGasLevel($row->close_time);
		$blob .= formatSiteInfo($row) . "\n\n";
	}

	$msg = Text::make_blob("All Bases in $playfield->long_name", $blob);

	$sendto->reply($msg);
} else if (preg_match("/^lc ([0-9a-z]+) ([0-9]+)$/i", $message, $arr)) {
	$playfield_name = strtoupper($arr[1]);
	$playfield = $playfields->get_playfield_by_name($playfield_name);
	if ($playfield === null) {
		$msg = "Playfield '$playfield_name' could not be found";
		$sendto->reply($msg);
		return;
	}

	$site_number = $arr[2];
	$sql = "SELECT * FROM tower_site t1
		JOIN playfields p ON (t1.playfield_id = p.id)
		WHERE t1.playfield_id = ? AND t1.site_number = ?";

	$data = $db->query($sql, $playfield->id, $site_number);
	$blob = '';
	forEach ($data as $row) {
		$gas_level = getGasLevel($row->close_time);
		$blob .= formatSiteInfo($row) . "\n\n";
	}

	if (count($data) > 0) {
		$msg = Text::make_blob("$playfield->short_name $site_number", $blob);
	} else {
		$msg = "Invalid site number.";
	}

	$sendto->reply($msg);
} else {
	$syntax_error = true;
}

?>
