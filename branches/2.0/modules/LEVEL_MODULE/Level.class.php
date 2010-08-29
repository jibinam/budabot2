<?php

class Level {
	public static function get_level_info($level) {
		global $db;
		
		$sql = "SELECT * FROM levels WHERE level = $level";
		return $db->query($sql, true);
	}
	
	public static function find_all_levels() {
		global $db;
		
		$sql = "SELECT * FROM levels ORDER BY level";
		return $db->query($sql);
	}
}

?>