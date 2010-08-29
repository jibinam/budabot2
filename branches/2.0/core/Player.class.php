<?php

class Player {

	private $uid;
	
	public static function create($input) {
		if (is_int($input)) {
			return new Player($input);
		} else {
			$uid = $chatBot->get_uid($input);
			if ($uid == false) {
				return null;
			} else {
				return new Player($uid);
			}
		}
	}

	private function __construct($uid) {
		$this->uid = $uid;
	}
	
	// gives property-like syntax
	public function __get($name) {
		$function_name = "get_" . $name;
		return $this->$function_name();
	}
	
	public function get_access_level() {
		return AccessLevel::get_user_access_level($this);
	}
	
	public function get_name() {
		global $chatBot;
		
		return $chatBot->get_uname($this->uid);
	}
	
	public function get_xml() {
		return new WhoisXML($this->get_name());
	}
	
	public function get_uid() {
		return $this->uid;
	}
	
	public function get_is_online() {
		$buddy = Buddylist::get($this->uid);
		return ($buddy === null ? null : $buddy['online']);
	}
	
	public function get_is_org_member() {
		// TODO
	}
	
	public function get_is_member() {
		// TODO
	}
	
	public function get_is_admin() {
		// TODO
	}
}

?>