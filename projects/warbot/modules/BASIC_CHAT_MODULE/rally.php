<?php

if (preg_match("/^rally$/i", $message)) {
  	// skip to end
} else if (preg_match("/^rally clear$/i", $message)) {
	Topic::clear_rally();
	$msg = "Rally has been cleared.";
	$chatBot->send($msg, $sendto);
	return;
} else if (preg_match("/^rally ([a-z0-9]+) ([0-9]+)$/i", $message)) {
	$playfield_name = $arr[1];
	$site_number = $arr[2];
	
	$playfield = Playfields::get_playfield_by_name($playfield_name);
	if ($playfield === null) {
		$msg = "Invalid playfield.";
		$chatBot->send($msg, $sendto);
		return;
	}
	
	$sql = "SELECT * FROM tower_site t1
		JOIN scout_info s ON (t1.playfield_id = s.playfield_id AND t1.site_number = s.site_number)
		JOIN tower_info t2 ON (t1.playfield_id = t2.playfield_id AND t1.site_number = t2.site_number)
		JOIN playfields p ON (t1.playfield_id = p.id)
		WHERE t1.playfield_id = $playfield->id AND t1.site_number = $site_number";

	$db->query($sql);
	$numrows = $db->numrows();
	
	if ($numrows > 0) {
		$row = $db->fObject();
		
		if (!is_null($row->x_rally) && !is_null($row->y_rally)) {
			Topic::set_rally("{$playfield->short_name} {$row->site_number}", $playfield->id, $row->x_coords, $row->y_coords);
		} else {
			$msg = "$playfield_name $site_number does not have a rally set.";
			$chatBot->send($msg, $sendto);
			return;
		}
	} else {
		$msg = "Invalid site number.";
		$chatBot->send($msg, $sendto);
		return;
	}
} else if (preg_match("/^rally \\(?([0-9\\.]+) ([0-9\\.]+) y ([0-9\\.]+) ([0-9]+)\\)?$/i", $message, $arr)) {
	$x_coords = $arr[1];
	$y_coords = $arr[2];
	$playfield_id = $arr[4];
	$name = $playfield_id;

	$playfield = Playfields::get_playfield_by_id($playfield_id);
	if ($playfield !== null) {
		$name = $playfield->short_name;
	}
	Topic::set_rally($name, $playfield_id, $x_coords, $y_coords);
} else if (preg_match("/^rally ([0-9\\.]+)([x,. ]+)([0-9\\.]+)([x,. ]+)([0-9]+)$/i", $message, $arr)) {
	$x_coords = $arr[1];
	$y_coords = $arr[3];
	$playfield_id = $arr[5];
	$name = $playfield_id;
	
	$playfield = Playfields::get_playfield_by_id($playfield_id);
	if ($playfield !== null) {
		$name = $playfield->short_name;
	}
	Topic::set_rally($name, $playfield_id, $x_coords, $y_coords);
} else if (preg_match("/^rally ([0-9\\.]+)([x,. ]+)([0-9\\.]+)([x,. ]+)(.+)$/i", $message, $arr)) {
	$x_coords = $arr[1];
	$y_coords = $arr[3];
	$playfield_name = $arr[5];
	
	$playfield = Playfields::get_playfield_by_name($playfield_name);
	if ($playfield === null) {
		$this->send("Could not find playfield '$playfield_name'", $sendto);
		return;
	}
	
	Topic::set_rally($playfield_name, $playfield->id, $x_coords, $y_coords);
} else {
	$syntax_error = true;
	return;
}

$rally = Topic::get_rally();
if ('' == $rally) {
	$msg = "No rally set.";
	$chatBot->send($msg, $sendto);
	return;
}
$this->send($rally, $sendto);

// send message 2 more times (3 total) if used in private channel
if ($type == "priv") {
	$chatBot->send($rally, $sendto);
	$chatBot->send($rally, $sendto);
}

?>