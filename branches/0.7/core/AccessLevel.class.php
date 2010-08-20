<?php

// Access levels
define('NOACCESS', -1);
define('SUPERADMIN', 0);
define('ADMIN', 1);
define('MODERATOR', 2);
define('RAIDLEADER', 3);
define('GUILDADMIN', 4);
define('LEADER', 5);
define('GUILDMEMBER', 6);
define('MEMBER', 7);
define('ALL', 8);

class AccessLevel {
/*===============================
** Name: getUserAccessLevel
** Returns the integer value that corresponds to an access level for the specified user
*/	public static function get_user_access_level($player) {
		global $chatBot;
		global $db;
		$user = ucfirst(strtolower($player->name));

		// covers superadmin, admin, moderator, raidleader
		if (isset($chatBot->admins[$user])) {
			return $chatBot->admins[$user]['level'];
		}
		
		// covers guildadmin
		if (isset($chatBot->guildmembers[$user]) && $chatBot->guildmembers[$sender] <= $chatBot->settings['guild admin level']) {
			return GUILDADMIN;
		}
		
		// covers leader
		if ($chatBot->vars["leader"] = $user) {
			return LEADER;
		}
		
		// covers guildmember
		if (isset($chatBot->guildmembers[$user])) {
			return GUILDMEMBER;
		}

		// covers member
		$db->query("SELECT * FROM members_<myname> WHERE `name` = '$user'");
	  	if ($db->numrows() != 0) {
	  		return MEMBER;
	  	}
		
		// covers all
		return ALL;
	}
	
/*===============================
** Name: getAccessDescription
** Returns the string value that corresponds to an access level
*/	public static function get_description($access_level) {
		$desc = '';
		switch ($access_level) {
			case NOACCESS:
				$desc = "No Access";
				break;
			case SUPERADMIN:
				$desc = "SuperAdmin";
				break;
			case ADMIN:
				$desc = "Admin";
				break;
			case MODERATOR:
				$desc = "Moderator";
				break;
			case RAIDLEADER:
				$desc = 'Raidleader';
				break;
			case GUILDADMIN:
				$desc = "GuildAdmin";
				break;
			case LEADER:
				$desc = "Leader";
				break;
			case GUILDMEMBER:
				$desc = "GuildMember";
				break;
			case MEMBER:
				$desc = "Member";
				break;
			case ALL:
				$desc = "All";
				break;
			default:
				$desc = "All";
				Logger::log(__FILE__, "Invalid access_level value specified: '$access_level'", ERROR);
		}
		return $desc;
	}
}

?>
