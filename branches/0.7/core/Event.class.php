<?php

class Event {

/*===============================
** Name: event
**  Registers an event
*/	public static function register_event($type, $module, $file, $dependson = '', $desc = '') {
		global $db;

	  	if (Settings::get('debug') > 1) print("Adding Event to list:($type) File:($file)\n");
		if (Settings::get('debug') > 2) sleep(1);

		if ($dependson == "none" && Settings::get("default module status") == 1) {
			$status = 1;
		} else {
			$status = 0;
		}

		if (($event = EVENT::get($type, $module, $file)) != false) {
		  	$db->query("UPDATE cmdcfg_<myname> SET `dependson` = '$dependson', `verify` = 1, `description` = '$desc' WHERE `type` = '$type' AND `cmdevent` = 'event' AND `file` = '$filename' AND `module` = '$module'");
		} else {
		  	$db->query("INSERT INTO cmdcfg_<myname> (`module`, `cmdevent`, `type`, `file`, `verify`, `dependson`, `description`, `status`) VALUES ('$module', 'event', '$type', '$filename', '1', '$dependson', '$desc', '$status')");
		}
	}
	
	public static function get_event($type, $module, $filename) {
		global $db;
		
		$sql = "SELECT * FROM eventcfg_<myname> WHERE `module` = '$module' AND `type` = '$type' AND `file` = '$file'";
		$db->query($sql);
		return $db->fObject();
	}
	
/*===============================
** Name: run_cron_jobs()
** Call php-Scripts at certin time intervals. 2 sec, 1 min, 15 min, 1 hour, 24 hours
*/	public static function run_cron_jobs() {
		global $db;
		global $chatBot;

		switch($chatBot->vars) {
			case $chatBot->vars["2sec"] < time();
				$chatBot->vars["2sec"] = time() + 2;
				forEach ($chatBot->spam as $key => $value) {
					if ($value > 0) {
						$chatBot->spam[$key] = $value - 10;
					} else {
						$chatBot->spam[$key] = 0;
					}
				}
				if ($chatBot->_2sec != NULL) {
					forEach ($chatBot->_2sec as $filename) {
						include $filename;
					}
				}
				break;
			case $chatBot->vars["1min"] < time();
				forEach ($chatBot->largespam as $key => $value) {
					if ($value > 0) {
						$chatBot->largespam[$key] = $value - 1;
					} else {
						$chatBot->largespam[$key] = 0;
					}
				}
				$chatBot->vars["1min"] = time() + 60;
				if ($chatBot->_1min != NULL) {
					forEach ($chatBot->_1min as $filename) {
						include $filename;
					}
				}
				break;
			case $chatBot->vars["10mins"] < time();
				$chatBot->vars["10mins"] = time() + (60 * 10);
				if ($chatBot->_10mins != NULL) {
					forEach ($chatBot->_10mins as $filename) {
						include $filename;
					}
				}
				break;
			case $chatBot->vars["15mins"] < time();
				$chatBot->vars["15mins"] = time() + (60 * 15);
				if ($chatBot->_15mins != NULL) {
					forEach ($chatBot->_15mins as $filename) {
						include $filename;
					}
				}
				break;
			case $chatBot->vars["30mins"] < time();
				$chatBot->vars["30mins"] = time() + (60 * 30);
				if ($chatBot->_30mins != NULL) {
					forEach ($chatBot->_30mins as $filename) {
						include $filename;
					}
				}
				break;
			case $chatBot->vars["1hour"] < time();
				$chatBot->vars["1hour"] = time() + (60 * 60);
				if ($chatBot->_1hour != NULL) {
					forEach ($chatBot->_1hour as $filename) {
						include $filename;
					}
				}
				break;
			case $chatBot->vars["24hours"] < time();
				$chatBot->vars["24hours"] = time() + ((60 * 60) * 24);
				if ($chatBot->_24hrs != NULL) {
					forEach ($chatBot->_24hrs as $filename) {
						include $filename;
					}
				}
				break;
		}
	}
}

?>