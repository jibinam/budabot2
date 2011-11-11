<?php

// to stop raising and lowering the cloak messages from triggering afk check
if (!Util::isValidSender($sender)) {
	return;
}

if (!preg_match("/^.?afk(.*)$/i", $message)) {
	$db->query("SELECT afk FROM online WHERE `name` = '{$sender}' AND added_by = '<myname>' AND channel_type = '$type'");
	$row = $db->fObject();

	if ($row != null && $row->afk != '') {
		$db->exec("UPDATE online SET `afk` = '' WHERE `name` = '{$sender}' AND added_by = '<myname>' AND channel_type = '$type'");
		$msg = "<highlight>{$sender}<end> is back";
		$chatBot->send($msg, $type);
	} else {
		list($name, $other) = explode(" ", $message, 2);
		$name = ucfirst(strtolower($name));

		if (isset($this->id[$name]) && Util::isValidSender($this->id[$name])) {
			$db->query("SELECT afk FROM online WHERE `name` = '" . str_replace("'", "''", $name) . "' AND added_by = '<myname>'");

			if ($db->numrows() != 0) {
				$row = $db->fObject();
				if ($row->afk == "1") {
					$msg = "<highlight>{$name}<end> is currently AFK.";
					$chatBot->send($msg, $type);
				} else if ($row->afk == "kiting") {
					$msg = "<highlight>{$name}<end> is currently Kiting.";
					$chatBot->send($msg, $type);
				} else if ($row->afk != "") {
					$msg = "<highlight>{$name}<end> is currently AFK: <highlight>{$row->afk}<end>";
					$chatBot->send($msg, $type);
				}
			}
		}
	}
}

?>