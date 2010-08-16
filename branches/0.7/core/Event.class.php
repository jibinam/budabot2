<?php

/*
`module` VARCHAR(50) NOT NULL
`type` VARCHAR(18)
`file` VARCHAR(255)
`is_core` TINYINT NOT NULL
`description` VARCHAR(50) NOT NULL DEFAULT ''
`verify` INT DEFAULT 0
`status` INT DEFAULT 1
*/

class Event {

/*===============================
** Name: event
**  Registers an event
*/	public static function register($type, $module, $filename, $desc = '', $is_core = 0) {
		global $db;

		Logger:log(__FILE__, "Adding Event to list:($type) File:($filename)", DEBUG);
		
		if (($filename = Util::verify_filename($filename)) == FALSE) {
			Logger:log(__FILE__, "Invalid filename: '$filename'", WARN);
		}

		if (Settings::get("default module status") == 1) {
			$status = 1;
		} else {
			$status = 0;
		}

		if (($event = EVENT::get($type, $module, $filename)) != false) {
		  	$db->query("UPDATE eventcfg_<myname> SET `verify` = 1, `description` = '$desc' WHERE `type` = '$type' `file` = '$filename' AND `module` = '$module'");
		} else {
		  	$db->query("INSERT INTO eventcfg_<myname> (`module`, `type`, `file`, `verify`, `description`, `status`, `is_core`) VALUES ('$module', '$type', '$filename', '1', '$desc', '$status', $is_core)");
		}
	}
	
	public static function get($type, $module, $filename) {
		global $db;
		
		$sql = "SELECT * FROM eventcfg_<myname> WHERE `module` = '$module' AND `type` = '$type' AND `file` = '$filename'";
		$db->query($sql);
		return $db->fObject();
	}
	
	public static function find_by_type($type) {
		global $db;
		
		$sql = "SELECT * FROM eventcfg_<myname> WHERE `type` = '$type' AND `status` = 1";
		$db->query($sql);
		return $db->fObject("all");
	}

/*===============================
** Name: run_cron_jobs()
** Call php-Scripts at certin time intervals. 2 sec, 1 min, 15 min, 1 hour, 24 hours
*/	public static function run_cron_jobs() {
		global $chatBot;

		switch($chatBot->vars) {
			case $chatBot->vars["2sec"] < time():
				$chatBot->vars["2sec"] = time() + 2;
				forEach ($chatBot->spam as $key => $value) {
					if ($value > 0) {
						$chatBot->spam[$key] = $value - 10;
					} else {
						$chatBot->spam[$key] = 0;
					}
				}
				forEach (Event::find_by_type('2sec') as $event) {
					include $event->filename;
				}
				break;
			case $chatBot->vars["1min"] < time():
				forEach ($chatBot->largespam as $key => $value) {
					if ($value > 0) {
						$chatBot->largespam[$key] = $value - 1;
					} else {
						$chatBot->largespam[$key] = 0;
					}
				}
				$chatBot->vars["1min"] = time() + 60;
				forEach (Event::find_by_type('1min') as $event) {
					include $event->filename;
				}
				break;
			case $chatBot->vars["10mins"] < time():
				$chatBot->vars["10mins"] = time() + (60 * 10);
				forEach (Event::find_by_type('10mins') as $event) {
					include $event->filename;
				}
				break;
			case $chatBot->vars["15mins"] < time():
				$chatBot->vars["15mins"] = time() + (60 * 15);
				forEach (Event::find_by_type('15mins') as $event) {
					include $event->filename;
				}
				break;
			case $chatBot->vars["30mins"] < time():
				$chatBot->vars["30mins"] = time() + (60 * 30);
				forEach (Event::find_by_type('30mins') as $event) {
					include $event->filename;
				}
				break;
			case $chatBot->vars["1hour"] < time():
				$chatBot->vars["1hour"] = time() + (60 * 60);
				forEach (Event::find_by_type('1hour') as $event) {
					include $event->filename;
				}
				break;
			case $chatBot->vars["24hours"] < time():
				$chatBot->vars["24hours"] = time() + ((60 * 60) * 24);
				forEach (Event::find_by_type('24hrs') as $event) {
					include $event->filename;
				}
				break;
		}
	}
}

?>