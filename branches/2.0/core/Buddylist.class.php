<?php

class Buddylist {
	public static function find_by_name($name) {
		global $chatBot;

		if (($uid = $chatBot->get_uid($name)) === false || !isset($chatBot->buddyList[$uid])) {
			return null;
		} else {
			return $chatBot->buddyList[$uid];
		}
    }
	
	public static function get($uid) {
		global $chatBot;

		return $chatBot->buddyList[$uid];
	}

/*===============================
** Name: buddy_online
** Returns null when online status is unknown, 1 when buddy is online, 0 when buddy is offline
*/	public static function is_online($name) {
		$buddy = Buddylist::find_by_name($name);
		return ($buddy === null ? null : $buddy['online']);
    }
	
	public static function add($uid, $type) {
		global $chatBot;

		if ($uid == false) {
			return false;
		} else {
			$name = $chatBot->buddyList[$uid]['name'];
			if (!isset($chatBot->buddyList[$uid])) {
				Logger::log(__FILE__, "{$name} buddy added", DEBUG);
				$chatBot->buddy_add($uid);
			}
			
			if (!isset($chatBot->buddyList[$uid]['types'][$type])) {
				$chatBot->buddyList[$uid]['types'][$type] = 1;
				Logger::log(__FILE__, "{$name} buddy type added (type: {$type})", DEBUG);
			}
			
			return true;
		}
	}
	
	public static function remove($uid, $type = '') {
		global $chatBot;

		if ($uid == false) {
			return false;
		} else if (isset($chatBot->buddyList[$uid])) {
			$name = $chatBot->buddyList[$uid]['name'];
			if (isset($chatBot->buddyList[$uid]['types'][$type])) {
				unset($chatBot->buddyList[$uid]['types'][$type]);
				Logger::log(__FILE__, "{name} buddy type removed (type: {$type})", DEBUG);
			}

			if (count($chatBot->buddyList[$uid]['types']) == 0) {
				unset($chatBot->buddyList[$uid]);
				Logger::log(__FILE__, "{$name} buddy removed", DEBUG);
				$chatBot->buddy_remove($uid);
			}
			
			return true;
		} else {
			return false;
		}
	}

	public static function is_buddy($uid, $type) {
		global $chatBot;

		if ($uid === false) {
			return false;
		} else {
			if ($type == null || $type == false) {
				return isset($chatBot->buddyList[$uid]);
			} else {
				return isset($chatBot->buddyList[$uid]['types'][$type]);
			}
		}
	}
	
	public static function store_buddy(&$player, $status, $known) {
		global $chatBot;
	
		// store buddy info
		$chatBot->buddyList[$player->uid]['uid'] = $player->uid;
		$chatBot->buddyList[$player->uid]['name'] = $player->name;
		$chatBot->buddyList[$player->uid]['online'] = $status;
		$chatBot->buddyList[$player->uid]['known'] = $known;
	}
}

?>