<?php

if (preg_match("/^scouthistory ([0-9a-z]+) ([0-9]+)$/i", $message, $arr)) {
	$playfield_name = $arr[1];
	$site_number = $arr[2];
	
	$playfield = Playfields::get_playfield_by_name($playfield_name);
	if ($playfield === null) {
		$msg = "Invalid playfield.";
		$chatBot->send($msg, $sendto);
		return;
	}
	
	$tower_info = Towers::get_tower_info($playfield->id, $site_number);
	if ($tower_info === null) {
		$msg = "Invalid site number.";
		$chatBot->send($msg, $sendto);
		return;
	}
	
	$data = $db->query("SELECT * FROM scout_info_history WHERE playfield_id = {$playfield->id} AND site_number = {$site_number}");
	if (count($data) === 0) {
		$msg = "No scout history entries are available.";
	} else {
		forEach ($data as $row) {
			$blob .= print_r($row, true);
		}
		$msg = Text::make_blob("History for $playfield->short_name $site_number", $blob);
	}
	
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>