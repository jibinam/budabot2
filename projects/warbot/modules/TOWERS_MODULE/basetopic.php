<?php

if (preg_match("/^basetopic ([0-9a-z]+) ([0-9]+)$/i", $message, $arr) || preg_match("/^basetopic ([0-9a-z]+) ([0-9]+) (.+)$/i", $message, $arr)) {
	$playfield_name = $arr[1];
	$site_number = $arr[2];
	$additional_comment = $arr[3];

	$playfield = Playfields::get_playfield_by_name($playfield_name);
	if ($playfield === null) {
		$msg = "Playfield '$playfield_name' could not be found";
		$chatBot->send($msg, $sendto);
		return;
	}

	$sql = "SELECT * FROM tower_site t1
			JOIN scout_info s ON (t1.playfield_id = s.playfield_id AND t1.site_number = s.site_number)
			JOIN tower_info t2 ON (t1.playfield_id = t2.playfield_id AND t1.site_number = t2.site_number)
			JOIN playfields p ON (t1.playfield_id = p.id)
			WHERE t1.playfield_id = $playfield->id AND t1.site_number = $site_number";

	$db->query($sql);
	if (($row = $db->fObject()) !== null) {
		$topic = "{$playfield->short_name} {$site_number}";
		
		if ($row->topic != '') {
			$topic .= ' - ' . $row->topic;
		}

		if (!is_null($row->x_rally) && !is_null($row->y_rally)) {
			Topic::set_rally("{$row->short_name} {$row->site_number}", $row->playfield_id, $row->x_rally, $row->y_rally);
		} else {
			Topic::clear_rally();
		}

		if ($additional_comment != '') {
			$topic .= ' - ' . $additional_comment;
		}

		Topic::set_topic($sender, $topic);
		
		$msg = "Update topic: " . Topic::get_topic();
	} else {
		$msg = "Invalid site number.";
	}

	bot::send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>