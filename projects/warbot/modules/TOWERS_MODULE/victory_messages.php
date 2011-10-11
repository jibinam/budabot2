<?php

if (preg_match("/^The (Clan|Neutral|Omni) organization (.+) attacked the (Clan|Neutral|Omni) (.+) at their base in (.+). The attackers won!!$/i", $message, $arr)) {
	$win_faction = $arr[1];
	$win_org_name = $arr[2];
	$lose_faction = $arr[3];
	$lose_org_name = $arr[4];
	$playfield_name = $arr[5];
} else if (preg_match("/^Notum Wars Update: The (Clan|Neutral|Omni) organization (.+) lost their base in (.+).$/i", $message, $arr)) {
	$win_faction = '';
	$win_org_name = '';
	$lose_faction = $arr[1];
	$lose_org_name = $arr[2];
	$playfield_name = $arr[3];
} else {
	return;
}
	
$playfield = Playfields::get_playfield_by_name($playfield_name);
if ($playfield === null) {
	Logger::log('error', 'Towers', "Could not find playfield for name '$playfield_name'");
	return;
}

$last_attack = Towers::get_last_attack($win_faction, $win_org_name, $lose_faction, $lose_org_name, $playfield->id);
if ($last_attack !== null) {
	$sql = "UPDATE scout_info SET `is_current` = 0 WHERE `playfield_id` = {$last_attack->playfield_id} AND `site_number` = {$last_attack->site_number} LIMIT 1";
	$db->exec($sql);

	// TODO set plant timer
} else {
	// manually initialize $last_attack object for recording the victory
	$last_attack = new stdClass;
	$last_attack->playfield_id = $playfield->id;
	$last_attack->site_number = '';
	$last_attack->att_org_name = $win_org_name;
	$last_attack->att_faction = $win_faction;
	$last_attack->def_org_name = $lose_org_name;
	$last_attack->def_faction = $lose_faction;

	$sql = "UPDATE scout_info SET `is_current` = 0 WHERE `playfield_id` = {$last_attack->playfield_id} AND `faction` = '{$last_attack->def_faction}' AND `org_name` = '{$last_attack->def_org_name}'";
	$db->exec($sql);
}

Towers::record_victory($last_attack);

?>
