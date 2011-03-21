<?php

if (isset($chatBot->guildmembers[$sender]) && $chatBot->is_ready()) {
	$whois = Player::get_by_name($sender);

	$msg = '';
	if ($whois === null) {
		$msg = "$sender logged on.";
	} else {
		$msg = Player::get_info($whois);

        $msg .= " logged on.";

        $alts = Alts::get_alts_blob($sender);
		if ($alts !== null) {
			$msg .= " $alts";
		}

		$sql = "SELECT logon_msg FROM org_members_<myname> WHERE name = '{$sender}'";
		$db->query($sql);
		$row = $db->fObject();
        if ($row !== null && $row->logon_msg != '') {
            $msg .= " - " . $row->logon_msg;
		}
	}

	$chatBot->send($msg, "guild", true);

	//private channel part
	if ($chatBot->settings["guest_relay"] == 1) {
		$chatBot->send($msg, "priv", true);
	}
}

?>
