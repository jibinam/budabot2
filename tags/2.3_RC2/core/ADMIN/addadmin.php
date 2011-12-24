<?php

if (preg_match("/^addadmin (.+)$/i", $message, $arr)){
	$who = ucfirst(strtolower($arr[1]));

	if ($chatBot->get_uid($who) == NULL){
		$chatBot->send("<red>Sorry the player you wish to add doesn't exist.<end>", $sendto);
		return;
	}
	
	if ($who == $sender) {
		$chatBot->send("<red>You can't add yourself to another group.<end>", $sendto);
		return;
	}
	
	$ai = Alts::get_alt_info($who);
	if (Setting::get("alts_inherit_admin") == 1 && $ai->main != $who) {
		$msg = "<red>Alts inheriting admin is enabled, and $who is not a main character.<end>";
		if ($chatBot->admins[$ai->main]["level"] == 4) {
			$msg .= " {$ai->main} is already an Administrator.";
		} else {
			$msg .= " Try again with $who's main, <highlight>{$ai->main}<end>.";
		}
		$chatBot->send($msg, $sendto);
		return;
	}

	if ($chatBot->admins[$who]["level"] == 4) {
		$chatBot->send("<red>Sorry but $who is already a Administrator.<end>", $sendto);
		return;
	}
	
	if (!AccessLevel::check_access($sender, 'superadmin')){
		$chatBot->send("<red>You need to be Super-Administrator to add a Administrator<end>", $sendto);
		return;
	}

	if (isset($chatBot->admins[$who]["level"]) && $chatBot->admins[$who]["level"] >= 2) {
		if ($chatBot->admins[$who]["level"] > 4) {
			$chatBot->send("<highlight>$who<end> has been demoted to an administrator.", $sendto);
			$chatBot->send("You have been demoted to an administrator", $who);
		} else {
			$chatBot->send("<highlight>$who<end> has been promoted to an administrator.", $sendto);
			$chatBot->send("You have been promoted to an administrator", $who);
		}
		$db->exec("UPDATE admin_<myname> SET `adminlevel` = 4 WHERE `name` = '$who'");
		$chatBot->admins[$who]["level"] = 4;
	} else {
		$db->exec("INSERT INTO admin_<myname> (`adminlevel`, `name`) VALUES (4, '$who')");
		$chatBot->admins[$who]["level"] = 4;
		$chatBot->send("<highlight>$who<end> has been added as an administrator", $sendto);
		$chatBot->send("You got administrator access to <myname>", $who);
	}

	Buddylist::add($who, 'admin');
} else {
	$syntax_error = true;
}
?>