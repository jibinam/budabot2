<?php

class AccessLevel extends Annotation {
	public static $ACCESS_LEVELS = array('none' => 0, 'superadmin' => 1,  'admin' => 2, 'mod' => 3, 'rl' => 4, 'guild' => 6, 'member' => 7, 'all' => 8);

	/** @Inject */
	public $db;
	
	/** @Inject */
	public $setting;
	
	/** @Inject */
	public $chatBot;
	
	/** @Inject */
	public $admin;
	
	/** @Logger */
	public $logger;

	/**
	 * @name: checkAccess
	 * @param: $sender - the name of the person you want to check access on
	 * @param: $accessLevel - can be one of: superadmin, admininistrator, moderator, raidleader, guild, member, all
	 * @returns: true if $sender has at least $accessLevel, false otherwise
	 */
	public function checkAccess($sender, $accessLevel) {
		$this->logger->log("DEBUG", "Checking access level '$accessLevel' against character '$sender'");
	
		$returnVal = $this->checkSingleAccess($sender, $accessLevel);
		
		if ($returnVal === false && $this->setting->get('alts_inherit_admin') == 1) {
			// if current character doesn't have access,
			// and if alts_inherit_admin is enabled,
			// and if the current character is not a main character,
			// and if the current character is validated,
			// then check access against the main character,
			// otherwise just return the result
			$altInfo = Alts::get_alt_info($sender);
			if ($sender != $altInfo->main && $altInfo->is_validated($sender)) {
				$this->logger->log("DEBUG", "Checking access level '$accessLevel' against the main of '$sender' which is '$altInfo->main'");
				$returnVal = $this->checkSingleAccess($altInfo->main, $accessLevel);
			}
		}
		
		return $returnVal;
	}
	
	public function checkSingleAccess($sender, $accessLevel) {
		$sender = ucfirst(strtolower($sender));
		$accessLevel = $this->normalizeAccessLevel($accessLevel);

		$charAccessLevel = $this->getSingleAccessLevel($sender);
		return ($this->compareAccessLevels($charAccessLevel, $accessLevel) >= 0);
	}
	
	public function normalizeAccessLevel($accessLevel) {
		$accessLevel = strtolower($accessLevel);
		switch ($accessLevel) {
			case "raidleader":
				$accessLevel = "rl";
				break;
			case "moderator":
				$accessLevel = "mod";
				break;
			case "administrator":
				$accessLevel = "admin";
				break;
		}
		
		return $accessLevel;
	}
	
	public function getDisplayName($accessLevel) {
		$displayName = strtolower($accessLevel);
		switch ($displayName) {
			case "rl":
				$displayName = "raidleader";
				break;
			case "mod":
				$displayName = "moderator";
				break;
			case "admin":
				$displayName = "administrator";
				break;
		}

		return $displayName;
	}
	
	public function getSingleAccessLevel($sender) {
		if ($this->chatBot->vars["SuperAdmin"] == $sender){
			return "superadmin";
		}
		if (isset($this->admin->admins[$sender])) {
			$level = $this->admin->admins[$sender]["level"];
			if ($level >= 4) {
				return "admin";
			}
			if ($level >= 3) {
				return "mod";
			}
		}
		if ($this->checkGuildAdmin($sender, 'admin')) {
			return "admin";
		}
		if ($this->checkGuildAdmin($sender, 'mod')) {
			return "mod";
		}
		if (isset($this->chatBot->data["leader"]) && $this->chatBot->data["leader"] == $sender) {
			return "rl";
		}
		if (isset($this->chatBot->guildmembers[$sender])) {
			return "guild";
		}
		
		$sql = "SELECT name FROM members_<myname> WHERE `name` = ?";
		$row = $this->db->queryRow($sql, $sender);
		if ($row !== null) {
			return "member";
		}
		return "all";
	}
	
	public function getAccessLevelForCharacter($sender) {
		$sender = ucfirst(strtolower($sender));

		$accessLevel = $this->getSingleAccessLevel($sender);
		
		if ($this->setting->get('alts_inherit_admin') == 1) {
			$altInfo = Alts::get_alt_info($sender);
			if ($sender != $altInfo->main && $altInfo->is_validated($sender)) {
				$mainAccessLevel = $this->getSingleAccessLevel($altInfo->main);
				if ($this->compareAccessLevels($mainAccessLevel, $accessLevel) > 0) {
					$accessLevel = $mainAccessLevel;
				}
			}
		}
		
		return $accessLevel;
	}
	
	public function checkGuildAdmin($sender, $accessLevel) {
		if (isset($this->chatBot->guildmembers[$sender]) && $this->chatBot->guildmembers[$sender] <= $this->setting->get('guild_admin_rank')) {
			if ($this->compareAccessLevels($this->setting->get('guild_admin_access_level'), $accessLevel) >= 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * @description: Returns a positive number if $accessLevel1 is a greater access level than $accessLevel2,
	 *               a negative number if $accessLevel1 is a lesser access level than $accessLevel2,
	 *               and 0 if the access levels are equal.
	 */
	public function compareAccessLevels($accessLevel1, $accessLevel2) {
		$accessLevel1 = $this->normalizeAccessLevel($accessLevel1);
		$accessLevel2 = $this->normalizeAccessLevel($accessLevel2);
	
		return AccessLevel::$ACCESS_LEVELS[$accessLevel2] - AccessLevel::$ACCESS_LEVELS[$accessLevel1];
	}
	
	public function compareCharacterAccessLevels($char1, $char2) {
		$char1 = ucfirst(strtolower($char1));
		$char2 = ucfirst(strtolower($char2));
		
		$char1AccessLevel = $this->getAccessLevelForCharacter($char1);
		$char2AccessLevel = $this->getAccessLevelForCharacter($char2);
		
		return $this->compareAccessLevels($char1AccessLevel, $char2AccessLevel);
	}
}

?>