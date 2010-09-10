<?php

if (preg_match("/^track$/i", $message)) {
	$db->query("SELECT * FROM tracked_users_<myname> ORDER BY `name`");
	$numrows = $db->numrows();
	if ($numrows != 0) {
	  	while ($row = $db->fObject()) {
			$tracked_player = Player::create($row->name);
	  	  	if ($tracked_player->is_online === 1) {
				$status = "<green>Online<end>";
			} else if ($tracked_player->is_online === 0) {
				$status = "<orange>Offline<end>";
			} else {
				$status = "<grey>Unknown<end>";
			}
			
			$history = Text::makeLink('History', "/tell <myname> track {$row->name}", 'chatcmd');

	  		$blob .= "<tab>- {$row->name} ({$status}) - {$history}\n";
	  	}
	  	
	    $msg = Text::makeBlob("<highlight>{$numrows}<end> players on the Track List", $blob);
		$chatBot->send($msg, $sendto);
	} else {
       	$chatBot->send("No players are on the track list.", $sendto);
	}
} else if (preg_match("/^track rem (.+)$/i", $message, $arr)) {
	$tracked_player = Player::create($arr[1]);
    
	if ($tracked_player == null) {
		$msg = "Player <highlight>{$arr[1]}<end> does not exist.";
		$chatBot->send($msg, $sendto);
		return;
	}

	$db->query("SELECT * FROM tracked_users_<myname> WHERE `uid` = {$tracked_player->uid}");
	if($db->numrows() == 0) {
		$msg = "<highlight>{$tracked_player->name}<end> is not on the track list.";
	} else {
		$db->query("DELETE FROM tracked_users_<myname> WHERE `uid` = {$tracked_player->uid}");
		$msg = "<highlight>{$tracked_player->name}<end> has been removed from the track list.";
		$tracked_player->remove_from_buddylist('tracking');
	}

	$chatBot->send($msg, $sendto);
} else if (preg_match("/^track add (.+)$/i", $message, $arr)) {
    $tracked_player = Player::create($arr[1]);

	if ($tracked_player == null) {
		$msg = "Player <highlight>{$arr[1]}<end> does not exist.";
		$chatBot->send($msg, $sendto);
		return;
	}

	$db->query("SELECT * FROM tracked_users_<myname> WHERE `uid` = {$tracked_player->uid}");
	if($db->numrows() != 0) {
		$msg = "<highlight>{$tracked_player->name}<end> is already on the track list.";
	} else {
		$db->query("INSERT INTO tracked_users_<myname> (`name`, `uid`, `added_by`, `added_dt`) VALUES ('{$tracked_player->name}', {$tracked_player->uid}, '$player->name', " . time() . ")");
		$msg = "<highlight>{$tracked_player->name}<end> has been added to the track list.";
		$tracked_player->add_to_buddylist('tracking');
	}

	$chatBot->send($msg, $sendto);
} else if (preg_match("/^track (.+)$/i", $message, $arr)) {
	$tracked_player = Player::create($arr[1]);
	
	if ($tracked_player == null) {
		$msg = "Player <highlight>{$arr[1]}<end> does not exist.";
		$chatBot->send($msg, $sendto);
		return;
	}
	
	$db->query("SELECT event, dt FROM tracking_<myname> WHERE `uid` = {$tracked_player->uid}");
	if ($db->numrows() != 0) {
	  	while ($row = $db->fObject()) {
	  		$blob .= "$row->event <white>" . date(DATE_RFC850, $row->dt) ."<end>\n";
	  	}

	    $msg = Text::makeBlob("Track History for {$tracked_player->name}", $blob);
	} else {
		$msg = "'{$tracked_player->name}' has never logged on or is not being tracked.";
	}
	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>
