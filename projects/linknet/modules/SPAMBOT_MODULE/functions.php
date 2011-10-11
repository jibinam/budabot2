<?php

function send_spam_to_orgbots($message) {
	global $chatBot;
	global $db;
	$time = time();
	
	$db->query("SELECT orgbot FROM spamorgbot");
	$data = $db->fObject('all');
	forEach ($data as $row) {
		$is_online = $chatBot->buddy_online($row->orgbot);
		if ($is_online === NULL) {
			$chatBot->add_buddy($row->orgbot, 'spambot');
		} else if ($is_online === 1) {
			$chatBot->send($message, $row->orgbot);

			$db->query("SELECT * FROM spamorgbot WHERE `name`= '$row->orgbot'");
			if ($db->numrows() == 0) {
				$db->query("SELECT * FROM spamlastseen WHERE `name`= '$row->orgbot'");
				if ($db->numrows() != 0) {
					$db->query("UPDATE spamlastseen SET `lastseen` = '$time' WHERE `name` = '$row->orgbot'");
				} else {
					$db->query("INSERT INTO spamlastseen (`name`, `lastseen`) VALUES ('$row->orgbot', '$time')");
				}
			}
		}
	}
}

function send_spam_to_slavebots($message) {
	global $chatBot;
	global $maxslaves;
	
	for ($i = 1; $i <= $maxslaves; $i++) {
		$slave = "Linknet".$i;
		$chatBot->send("spamrelay $message", $slave);
	}
}

function send_spam_to_members($slaveid, $spamtype, $message) {
	global $chatBot;
	global $db;
	$time = time();
	
	$spamtype = strtolower($spamtype);
	
	$db->query("SELECT * FROM spammembers WHERE `server` = '$slaveid' ORDER BY name ASC");
	$data = $db->fObject('all');
	forEach ($data as $row) {
		$is_online = $chatBot->buddy_online($row->name);
		if ($is_online === NULL) {
			$chatBot->add_buddy($row->name, 'spammember');
		} else if ($is_online === 1) {
			if ($spamtype == "general" && ($row->general != "yes")) {
				continue;
			} else if ($spamtype == "shopping" && ($row->shopping != "yes")) {
				continue;
			} else if ($spamtype == "raids" && ($row->raids != "yes")) {
				continue;
			} else if ($spamtype == "pvp" && ($row->pvp != "yes")) {
				continue;
			} else if ($spamtype == "tarabot" && ($row->taranet != "yes")) {
				continue;
			} else if ($spamtype == "pvpnet" && ($row->pvpnet != "yes")) {
				continue;
			} else if ($spamtype == "twinknet" && ($row->twinknet != "yes")) {
				continue;
			} else if ($spamtype == "admin") {
				// do nothing
			} else {
				//newLine("Error", "Invalid spamtype: '$spamtype'");
				//break;
			}
			
			$chatBot->send($message, $row->name);

			$db->query("SELECT * FROM spamorgbot WHERE `name`= '$row->name'");
			if ($db->numrows() == 0) {
				$db->query("SELECT * FROM spamlastseen WHERE `name`= '$row->name'");
				if ($db->numrows() != 0) {
					$db->query("UPDATE spamlastseen SET `lastseen` = '$time' WHERE `name` = '$row->name'");
				} else {
					$db->query("INSERT INTO spamlastseen (`name`, `lastseen`) VALUES ('$row->name', '$time')");
				}
			}
		}
	}
}