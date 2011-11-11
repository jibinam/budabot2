<?php

class AltInfo {
	public $main; // The main for this character
	public $alts = array(); // The list of alts for this character
	
	public function is_validated($sender) {
		if ($sender == $this->main) {
			return true;
		}
		
		forEach ($this->alts as $alt => $validated) {
			if ($sender == $alt) {
				return ($validated == 1);
			}
		}
		
		// $sender is not an alt at all, return false
		return false;
	}
	
	public function get_alts_blob($showValidateLinks = false) {
		$db = DB::get_instance();
		
		if (count($this->alts) == 0) {
			return "No registered alts";
		}

		$list = "<header> :::::: Character List for {$this->main} :::::: <end>\n\n";
		$list .= "<tab><tab>{$this->main}";
		$character = Player::get_by_name($this->main);
		if ($character !== null) {
			$list .= " (<highlight>{$character->level}<end>/<green>{$character->ai_level}<end> <highlight>{$character->profession}<end>)";
		}
		$online = Buddylist::is_online($this->main);
		if ($online === null) {
			$list .= " - No status.\n";
		} else if ($online == 1) {
			$list .= " - <green>Online<end>\n";
		} else {
			$list .= " - <red>Offline<end>\n";
		}
		$list .= "\n:::::: Alt Character(s)\n";
		
		$sql = "SELECT `alt`, `main`, `validated`, p.* FROM `alts` a LEFT JOIN players p ON (a.alt = p.name AND p.dimension = '<dim>') WHERE `main` LIKE '{$this->main}' ORDER BY level DESC, ai_level DESC, profession ASC, name ASC";
		$db->query($sql);
		$data = $db->fObject('all');
		forEach ($data as $row) {
			$list .= "<tab><tab>{$row->alt}";
			if ($row->profession !== null) {
				$list .= " (<highlight>{$row->level}<end>/<green>{$row->ai_level}<end> <highlight>{$row->profession}<end>)";
			}
			$online = Buddylist::is_online($row->alt);
			if ($online === null) {
				$list .= " - No status.";
			} else if ($online == 1) {
				$list .= " - <green>Online<end>";
			} else {
				$list .= " - <red>Offline<end>";
			}
			
			if ($showValidateLinks && Setting::get('alts_inherit_admin') == 1 && $row->validated == 0) {
				$list .= " [Unvalidated] " . Text::make_link('Validate', "/tell <myname> <symbol>altvalidate {$row->alt}", 'chatcmd');
			}
			
			$list .= "\n";
		}
		
		$msg = Text::make_blob("Alts of {$this->main}", $list);
		
		return $msg;
	}

	public function get_online_alts() {
		$online_list = array();

		if (Buddylist::is_online($this->main)) {
			$online_list []= $this->main;
		}
		
		forEach ($this->alts as $name => $validated) {
			if (Buddylist::is_online($name)) {
				$online_list []= $name;
			}
		}
		
		return $online_list;
	}
	
	public function get_all_alts() {
		$online_list = array();

		$online_list []= $this->main;
		
		forEach ($this->alts as $name => $validated) {
			$online_list []= $name;
		}
		
		return $online_list;
	}
	
	public function hasUnvalidatedAlts() {
		forEach ($this->get_all_alts() as $alt) {
			if (!$this->is_validated($alt)) {
				return true;
			}
		}
		return false;
	}
}

class Alts {
	public static function get_alt_info($player) {
		$db = DB::get_instance();
		
		$player = ucfirst(strtolower($player));
		
		$ai = new AltInfo();
		
		$sql = "SELECT `alt`, `main`, `validated` FROM `alts` WHERE (`main` LIKE '$player') OR (`main` LIKE (SELECT `main` FROM `alts` WHERE `alt` LIKE '$player'))";
		$db->query($sql);
		
		$isValidated = 0;
		
		$data = $db->fObject('all');
		if (count($data) > 0) {
			forEach ($data as $row) {
				$ai->main = $row->main;
				$ai->alts[$row->alt] = $row->validated;
			}
		} else {
			$ai->main = $player;
		}
		
		return $ai;
	}
	
	public static function add_alt($main, $alt, $validated) {
		$db = DB::get_instance();
		
		$main = ucfirst(strtolower($main));
		$alt = ucfirst(strtolower($alt));
		
		$sql = "INSERT INTO `alts` (`alt`, `main`, `validated`) VALUES ('$alt', '$main', '$validated')";
		return $db->exec($sql);
	}
	
	public static function rem_alt($main, $alt) {
		$db = DB::get_instance();
		
		$sql = "DELETE FROM `alts` WHERE `alt` LIKE '$alt' AND `main` LIKE '$main'";
		return $db->exec($sql);
	}
}

?>