<?php

class Ban {
	public static function add($char, $sender, $length, $reason) {
		$db = DB::get_instance();
		
		if ($length == null) {
			$ban_end = "NULL";
		} else {
			$ban_end = time() + $length;
		}

		$sql = "INSERT INTO banlist_<myname> (`name`, `admin`, `time`, `reason`, `banend`) VALUES (?, ?, ?, ?, ?)";
		$numrows = $db->exec($sql, $char, $sender, time(), $reason, $ban_end);
		
		Ban::upload_banlist();
		
		return $numrows;
	}
	
	public static function remove($char) {
		$db = DB::get_instance();

		$sql = "DELETE FROM banlist_<myname> WHERE name = ?";
		$numrows = $db->exec($sql, $char);
		
		Ban::upload_banlist();
		
		return $numrows;
	}
	
	public static function upload_banlist() {
		$db = DB::get_instance();
		global $chatBot;
		
		$chatBot->banlist = array();
		
		$data = $db->query("SELECT * FROM banlist_<myname>");
		forEach ($data as $row) {
			$chatBot->banlist[$row->name] = $row;
		}
	}
	
	public static function is_banned($char) {
		global $chatBot;
	
		return isset($chatBot->banlist[$char]);
	}
}

?>