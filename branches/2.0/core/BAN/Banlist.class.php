<?php

class Banlist {
	public static function get(&$player) {
		global $db;
		
		$params = array(':uid' => $player->uid);
		$sql = "SELECT * FROM banlist WHERE `uid` = :uid";
		return $db->prepared_statement($sql, $params, true);
	}
	
	public static function add(&$who, &$sender, $reason, $banend) {
		global $db;
		
		$params = array(':who' => $who->uid, ':banned_by' => $sender->uid, ':time' => time(), ':reason' => $reason, ':banend' => $banend);
		$sql =
			"INSERT INTO banlist_<myname> (
				`who`,
				`banned_by`,
				`time`,
				`reason`,
				`banend`
			) VALUES (
				:who,
				:banned_by,
				:time,
				:reason,
				:banend
			)";
			
		$db->prepared_statement($sql, $params);
	}
	
	public static function find_all() {
		global $db;
		
		$sql = "SELECT who, banned_by, time, reason, banend FROM banlist_<myname>";
		return $db->query($sql);
	}
	
	public static function remove(&$player) {
		global $db;
		
		$params = array(':who' => $player->uid);
		$sql = "DELETE FROM banlist_<myname> WHERE name = :name";
		$db->prepared_statement($sql, $params);
	}
	
	public static function remove_expired_bans() {
		global $db;
		
		$params = array(':banend' => time());
		$sql = "DELETE FROM banlist_<myname> WHERE banend < :banend";
		$db->prepared_statement($sql, $params);
	}
}

?>