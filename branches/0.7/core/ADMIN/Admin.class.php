<?php

class Admin {
	public static function add($uid, $acces_level) {
		global $db;

		$db->exec("INSERT INTO admin_<myname> (`access_level`, `uid`) VALUES ($acces_level, $uid)");
	}
	
	public static function remove($uid) {
		global $db;

		$db->exec("DELETE FROM admin_<myname> WHERE `uid` = $uid");
	}
	
	public static function get($uid) {
		global $db;
		
		return $db->query("SELECT * FROM admin_<myname> WHERE `uid` = $uid", true);
	}
	
	public static function find_all() {
		global $db;
		
		return $db->query("SELECT * FROM admin_<myname> ORDER BY `access_level` ASC");
	}
	
	public static function update($uid, $access_level) {
		global $db;
		
		$db->exec("UPDATE admin_<myname> SET `access_level` = $access_level WHERE `uid` = $uid");
	}
	
	public static function find_by_access_level($access_level) {
		global $db;
		
		return $db->query("SELECT * FROM admin_<myname> WHERE `access_level` = $access_level");
	}
	
	public static function add_or_update_admin(&$sendto, &$player, &$who, $access_level) {
		global $chatBot;
		
		$description = AccessLevel::get_description($access_level);
		
		if ($who->uid == NULL){
			$chatBot->send("<red>Error! Player $who->name does not exist.", $sendto);
			return;
		}

		if ($who->access_level == RAIDLEADER) {
			$chatBot->send("<red>Error! $who->name is already a(n) $description.<end>", $sendto);
			return;
		}

		if ($player->access_level >= $who->access_level) {
			$chatBot->send("<red>Error! You must have a higher access level than $who->name to modify his/her access.<end>", $sendto);
			return;
		}
		
		$admin = Admin::get($who->uid);
		if ($admin == false) {
			Admin::add($who->uid, $access_level);
			$chatBot->send("<highlight>$who->name<end> has been added as a(n) $description.", $sendto);
			$chatBot->send("You have been added as a(n) $description.", $who);
		} else if ($admin->access_level < $access_level) {
			Admin::update($who->uid, $access_level);
			$chatBot->send("<highlight>$who->name<end> has been demoted to a(n) $description.", $sendto);
			$chatBot->send("You have been demoted to a(n) $description.", $who);
		} else if ($admin->access_level > $access_level) {
			Admin::update($who->uid, $access_level);
			$chatBot->send("<highlight>$who->name<end> has been promoted to a(n) $description.", $sendto);
			$chatBot->send("You have been promoted to a(n) $description.", $who);
		} else {
			// no change
		}
		
		Buddylist::add($who->uid, 'admin');
	}
	
	public static function remove_admin(&$sendto, &$player, &$who, $access_level) {
		global $chatBot;
		
		$description = AccessLevel::get_description($access_level);

		if ($who->uid == NULL){
			$chatBot->send("<red>Error! Player $who->name does not exist.", $sendto);
			return;
		}

		if ($who->access_level != $access_level) {
			$chatBot->send("<red>Error! $who->name is not a(n) $description.<end>", $sendto);
			return;
		}

		if ($player->access_level >= $who->access_level) {
			$chatBot->send("<red>Error! You must have a higher access level than $who->name to modify his/her access.<end>", $sendto);
			return;
		}

		Admin::remove($who->uid);
		
		$chatBot->send("<highlight>$who->name<end> has been removed as a(n) $description.", $sendto);
		$chatBot->send("You have been removed as a(n) $description.", $who);
		
		Buddylist::remove($uid, 'admin');
	}
}

?>