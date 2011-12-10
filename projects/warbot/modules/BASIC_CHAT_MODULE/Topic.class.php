<?php

class Topic {
	public static function set_topic($user, $topic) {
		Setting::save("topic_time", time());
		Setting::save("topic_setby", $user);
		Setting::save("topic", $topic);
	}
	
	public static function get_topic() {
		$date_string = Util::unixtime_to_readable(time() - Setting::get("topic_time"), false);
		$setBy = Setting::get('topic_setby');
		$topic = Setting::get("topic");
		if ($topic == '') {
			$topic = 'No topic set';
		}
		
		$rally = Topic::get_rally();
		if ($rally != '') {
			$topic .= ' (' . $rally . ')';
		}
		
		$msg = "{$topic} [set by <highlight>{$setby}<end>][<highlight>{$date_string} ago<end>]";
		
		return $msg;
	}
	
	public static function set_rally($name, $playfield_id, $x_coords, $y_coords) {
		$link = Text::make_chatcmd("Rally: {$x_coords}x{$y_coords} {$name}", "/waypoint {$x_coords} {$y_coords} {$playfield_id}");
		$blob = "<header>:::::: Rally ({$name}) ::::::<end>\n\nClick here to use rally: $link";
		$rally = Text::make_blob("Rally: {$x_coords}x{$y_coords} {$name}", $blob);
		
		Setting::save("rally", $rally);
		
		return $rally;
	}
	
	public static function get_rally() {
		return Setting::get("rally");
	}
	
	public static function clear_rally() {
		Setting::save("rally", '');
	}
}

?>
