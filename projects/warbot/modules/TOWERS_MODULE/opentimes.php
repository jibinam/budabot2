<?php

if (preg_match("/^opentimes (\\d+) (\\d+)$/i", $message, $arr)) {

	$lowql = $arr[1];
	$highql = $arr[2];

	$title = "Scouted Clan bases with CT QL {$lowql}-{$highql}";
	$side_sql = "AND (s.faction = 'Clan')";
	
	$sql = "
		SELECT
			*
		FROM
			tower_site t
			JOIN scout_info s ON (t.playfield_id = s.playfield_id AND s.site_number = t.site_number)
			JOIN playfields p ON (t.playfield_id = p.id)
		WHERE
			(s.ct_ql BETWEEN $lowql AND $highql)
			$side_sql
		ORDER BY
			close_time";
	$db->query($sql);
	$numrows = $db->numrows();
	
	$blob = '';
	while (($row = $db->fObject()) != false) {
		$gas_level = getGasLevel($row->close_time);
		$gas_change_string = "$gas_level->color $gas_level->gas_level - $gas_level->next_state in " . gmdate('H:i:s', $gas_level->gas_change) . "<end>";

		$site_link = Text::make_chatcmd("$row->short_name $row->site_number", "/tell <myname> lc $row->short_name $row->site_number");
		$open_time = $row->close_time - (3600 * 6);
		if ($open_time < 0) {
			$open_time += 86400;
		}
		
		$blob .= "$site_link <white>- {$row->min_ql}-{$row->max_ql}, $row->ct_ql CT, $row->org_name, open from " . gmdate('H:i:s', $open_time) . " to " . gmdate('H:i:s', $row->close_time) . " [by $row->scouted_by]<end>\n";
	}
	
	if ($numrows > 0) {
		$msg = Text::make_blob($title, $title . "\n\n" . $blob);
	} else {
		$msg = "No sites found.";
	}
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>