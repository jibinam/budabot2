<?php

class Topic {
	public static function set_topic($user, $topic) {
		global $chatBot;
	
		$chatBot->savesetting("topic_time", time());
		$chatBot->savesetting("topic_setby", $user);
		$chatBot->savesetting("topic", $topic);
	}
	
	public static function get_topic() {
		global $chatBot;
	
		$date_string = unixtime_to_readable(time() - $chatBot->settings["topic_time"], false);
		if ($chatBot->settings["topic"] == '') {
			$topic = 'No topic set';
		} else {
			$topic = $chatBot->settings["topic"];
		}
		
		$rally = Topic::get_rally();
		if ($rally != '') {
			$topic .= ' (' . $rally . ')';
		}
		
		$msg = "{$topic} [set by <highlight>{$chatBot->settings["topic_setby"]}<end>][<highlight>{$date_string} ago<end>]";
		
		return $msg;
	}
	
	public static function set_rally($name, $playfield_id, $x_coords, $y_coords) {
		global $chatBot;
		
		$link = $chatBot->makeLink("Rally: {$x_coords}x{$y_coords} {$name}", "/waypoint {$x_coords} {$y_coords} {$playfield_id}", 'chatcmd');	
		$blob = "<header>:::::: Rally ({$name}) ::::::<end>\n\nClick here to use rally: $link";
		$rally = $chatBot->makeLink("Rally: {$x_coords}x{$y_coords} {$name}", $blob, 'blob');
		
		$chatBot->savesetting("rally", $rally);
		
		return $rally;
	}
	
	public static function get_rally() {
		global $chatBot;

		return $chatBot->settings["rally"];
	}
	
	public static function clear_rally() {
		global $chatBot;
		
		$chatBot->savesetting("rally", '');
	}
}

?>
