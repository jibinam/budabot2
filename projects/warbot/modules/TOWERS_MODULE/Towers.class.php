<?php

class Towers {
	public static function get_tower_info($playfield_id, $site_number) {
		$db = db::get_instance();

		$sql = "
			SELECT
				*
			FROM
				tower_site t
			WHERE
				`playfield_id` = {$playfield_id}
				AND `site_number` = {$site_number}
			LIMIT 1";
		
		$db->query($sql);
		return $db->fObject();
	}
	
	public static function find_sites_in_playfield($playfield_id) {
		$db = db::get_instance();

		$sql = "SELECT * FROM tower_site WHERE `playfield_id` = {$playfield_id}";

		$db->query($sql);
		return $db->fObject('all');
	}
	
	public static function get_closest_site($playfield_id, $x_coords, $y_coords) {
		$db = db::get_instance();

		$sql = "
			SELECT
				*,
				((x_distance * x_distance) + (y_distance * y_distance)) radius
			FROM
				(SELECT
					playfield_id,
					site_number,
					min_ql,
					max_ql,
					x_coord,
					y_coord,
					site_name,
					(x_coord - {$x_coords}) as x_distance,
					(y_coord - {$y_coords}) as y_distance
				FROM
					tower_site
				WHERE
					playfield_id = {$playfield_id}) t
			ORDER BY
				radius ASC
			LIMIT 1";

		$db->query($sql);
		return $db->fObject();		
	}

	public static function get_last_attack($att_faction, $att_guild_name, $def_faction, $def_guild_name, $playfield_id) {
		$db = db::get_instance();
		
		$att_guild_name = str_replace("'", "''", $att_guild_name);
		$def_guild_name = str_replace("'", "''", $def_guild_name);
		
		$time = time() - (7 * 3600);
		
		$sql = "
			SELECT
				*
			FROM
				tower_attack_<myname>
			WHERE
				`att_guild_name` = '{$att_guild_name}'
				AND `att_faction` = '{$att_faction}'
				AND `def_guild_name` = '{$def_guild_name}'
				AND `def_faction` =  '{$def_faction}'
				AND `playfield_id` = {$playfield_id}
				AND `time` >= {$time}
			ORDER BY
				`time` DESC
			LIMIT 1";
		
		$db->query($sql);
		return $db->fObject();
	}
	
	public static function record_attack($whois, $def_faction, $def_guild_name, $x_coords, $y_coords, $closest_site) {
		$db = db::get_instance();
		
		$att_guild_name = str_replace("'", "''", $whois->guild);
		$def_guild_name = str_replace("'", "''", $def_guild_name);
		
		$sql = "
			INSERT INTO tower_attack_<myname> (
				`time`,
				`att_guild_name`,
				`att_faction`,
				`att_player`,
				`att_level`,
				`att_profession`,
				`def_guild_name`,
				`def_faction`,
				`playfield_id`,
				`site_number`,
				`x_coords`,
				`y_coords`
			) VALUES (
				".time().",
				'{$att_guild_name}',
				'{$whois->faction}',
				'{$whois->name}',
				'{$whois->level}',
				'{$whois->profession}',
				'{$def_guild_name}',
				'{$def_faction}',
				{$closest_site->playfield_id},
				{$closest_site->site_number},
				{$x_coords},
				{$y_coords}
			)";
		
		return $db->exec($sql);
	}
	
	public static function find_all_scouted_sites() {
		$db = db::get_instance();
		
		$sql = 
			"SELECT
				*
			FROM
				scout_info s
				JOIN tower_site t
					ON (s.playfield_id = t.playfield_id AND s.site_number = t.site_number)
				JOIN playfields p
					ON (s.playfield_id = p.id)
			ORDER BY
				org_name, ct_ql";

		$db->query($sql);
		return $db->fObject('all');
	}
	
	public static function get_last_victory($playfield_id, $site_number) {
		$db = db::get_instance();
		
		$sql = "
			SELECT
				*
			FROM
				tower_victory_<myname> v
				JOIN tower_attack_<myname> a ON (v.attack_id = a.id)
			WHERE
				a.`playfield_id` = {$playfield_id}
				AND a.`site_number` >= {$site_number}
			ORDER BY
				v.`time` DESC
			LIMIT 1";
		
		$db->query($sql);
		return $db->fObject();
	}
	
	public static function record_victory($last_attack) {
		$db = db::get_instance();
		
		$win_guild_name = str_replace("'", "''", $last_attack->att_guild_name);
		$lose_guild_name = str_replace("'", "''", $last_attack->def_guild_name);
		
		$sql = "
			INSERT INTO tower_victory_<myname> (
				`time`,
				`win_guild_name`,
				`win_faction`,
				`lose_guild_name`,
				`lose_faction`,
				`attack_id`
			) VALUES (
				".time().",
				'{$win_guild_name}',
				'{$last_attack->att_faction}',
				'{$lose_guild_name}',
				'{$last_attack->def_faction}',
				{$last_attack->id}
			)";
		
		return $db->exec($sql);
	}
	
	public static function scout_site($playfield_id, $site_number, $close_time, $ct_ql, $faction, $org_name, $scouted_by) {
		$db = db::get_instance();
		
		$org_name = str_replace("'", "''", $org_name);
		
		$sql = "
			INSERT INTO scout_info_history (
				`playfield_id`,
				`site_number`,
				`scouted_on`,
				`scouted_by`,
				`ct_ql`,
				`org_name`,
				`faction`,
				`close_time`
			) VALUES (
				{$playfield_id},
				{$site_number},
				".time().",
				'{$scouted_by}',
				{$ct_ql},
				'{$org_name}',
				'{$faction}',
				{$close_time}
			)";

		$db->exec($sql);

		$sql = "
			UPDATE scout_info SET 
				`scouted_on` = NOW(),
				`scouted_by` = '{$scouted_by}',
				`ct_ql` = {$ct_ql},
				`org_name` = '{$org_name}',
				`faction` = '{$faction}',
				`close_time` = {$close_time},
				`is_current` = 1
			WHERE
				`playfield_id` = {$playfield_id}
				AND `site_number` = {$site_number}";

		$db->exec($sql);
	}
	
	public static function check_org_name($org_name) {
		$db = db::get_instance();
		
		$org_name = str_replace("'", "''", $org_name);
	
		$sql = "SELECT * FROM tower_attack_<myname> WHERE `att_guild_name` LIKE '{$org_name}' OR `def_guild_name` LIKE '{$org_name}' LIMIT 1";
		
		$db->query($sql);
		if ($db->numrows() === 0) {
			return false;
		} else {
			return true;
		}
	}
}

?>