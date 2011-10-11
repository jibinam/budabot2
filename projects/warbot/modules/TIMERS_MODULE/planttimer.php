<?php

if (!function_exists('set_timer')) {
	function set_timer($name, $sender, $run_time, $callback_params) {
		global $chatBot;
		global $db;
		
		$timer = time() + $run_time;

		$chatBot->vars["Timers"][] = (object)array("name" => $timer_name, "owner" => $sender, "mode" => 'planttimer', "timer" => $timer, "settime" => time(), "callback" => "planttimer_callback", "callback_param" => $callback_param);
		$db->query("INSERT INTO timers_<myname> (`name`, `owner`, `mode`, `timer`, `settime`) VALUES ('".str_replace("'", "''", $timer_name)."', '$sender', 'planttimer', $timer, ".time().")");
	}
}

if (!function_exists('check_for_planttimer')) {
	function check_for_planttimer($victory_id) {
		global $chatBot;
		
		forEach ($chatBot->vars["Timers"] as $timer) {
			if ($timer->callback_param == $victory_id) {
				return true;
			}
		}
		return false;
	}
}

$time = time() - 1200;  // 20 minutes

if (preg_match("/^planttimer$/i", $message, $arr)) {
	// don't include sites that already have times
	$ids = array();
	forEach ($this->vars['Timers'] as $timer) {
		if ($timer->mode == 'planttimer') {
			$ids []= $timer->callback_param;
		}
	}
	$ids = implode(', ', $ids);
	
	$sql = "
		SELECT
			v.id AS victory_id,
			*
		FROM
			victory v 
			JOIN battle b ON (v.attack_id = b.id)
			JOIN playfields p ON (b.playfield_id = p.id)
			JOIN scout_info s ON (b.playfield_id = s.playfield_id AND b.site_number = s.site_number)
		WHERE
			v.time > {$time}
			AND v.id NOT IN ({$ids})";
			
	$data = $db->fObject($sql);
	
	// remove sites that already have a planttimer set
	forEach ($data as $key => $row) {
		if (check_for_planttimer($row->victory_id)) {
			unset($data[$key]);
		}
	}
		
	$numrows = count($data);
	if ($numrows == 0) {
		$msg = "No sites have been destroyed in the past 20 minutes.";
	} else if ($numrows == 1) {
		$row = $data[0];
		$name = "{$row->lose_org_name}'s previous base at {$row->short_name} {$row->site_number}";
		$timer = time() - $row->timer;
		set_timer($name, $sender, $timer, $row->victory_id);
		
		$date_string = unixtime_to_readable(time() - $row->timer);
		$msg = "Timer has been set for {$date_string} for {$row->playfield_id} {$row->site_number}";
	} else {
		$blob = "<header>:::::: Sites destroyed in the past 20 minutes ::::::<end>\n\n";
		forEach ($data as $row) {
			$date_string = unixtime_to_readable(time() - $row->timer);
			$link = $this->makeLink("{$row->lose_org_name}'s previous base at {$row->short_name} {$row->site_number}", "/tell <myname> planttimer {$row->victory_id}", 'chatcmd');
			$blob .= "{$link} {$date_string} ago \n";
		}
		$msg = $this->makeLink('Sites destroyed in the past 20 minutes', $blob, 'blob');
	}
	$this->send($msg, $sendto);
} else if (preg_match("/^planttimer ([0-9]+)$/i", $message, $arr)) {
	$victory_id = $arr[1];

	$sql = "
		SELECT
			v.id AS victory_id,
			*
		FROM
			victory v 
			JOIN battle b ON (v.attack_id = b.id)
			JOIN playfields p ON (b.playfield_id = p.id)
			JOIN scout_info s ON (b.playfield_id = s.playfield_id AND b.site_number = s.site_number)
		WHERE
			v.time > {$time}
			AND v.id = {$victory_id})";
			
	$data = $db->fObject($sql);
	$numrows = count($data);
	if ($numrows == 0) {
		$msg = "Invalid victory id or site was destroyed more than 20 minutes ago.";
	} else {
		$row = $data[0];
		if (check_for_planttimer($row->victory_id)) {
			$msg = "Timer has already been set for this site.";
			$this->send($msg, $sendto);
			return;
		}

		$name = "{$row->lose_org_name}'s previous base at {$row->short_name} {$row->site_number}";
		$timer = time() - $row->timer;
		set_timer($name, $sender, $timer, $row->victory_id);
		
		$date_string = unixtime_to_readable(time() - $row->timer);
		$msg = "Timer has been set for {$date_string} for {$row->playfield_id} {$row->site_number}";
	}
	$this->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>