<?php

class Level {
	public static function get_level_info($level) {
		$db = DB::get_instance();
		
		$sql = "SELECT * FROM levels WHERE level = $level";
		$db->query($sql);
		return $db->fObject();
	}
	
	public static function find_all_levels() {
		$db = DB::get_instance();
		
		$sql = "SELECT * FROM levels ORDER BY level";
		$db->query($sql);
		return $db->fObject('all');
	}
}

?>