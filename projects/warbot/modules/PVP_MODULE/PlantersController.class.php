<?php

/**
 * Authors: 
 *	- Tyrence (RK2)
 *
 * @Instance
 *
 * Commands this controller contains:
 *	@DefineCommand(
 *		command     = 'planters', 
 *		accessLevel = 'all', 
 *		description = 'Show orgs who are capable of planting a site', 
 *		help        = 'planters.txt'
 *	)
 */
class PlantersController {

	/**
	 * Name of the module.
	 * Set automatically by module loader.
	 */
	public $moduleName;

	/** @Inject */
	public $text;
	
	/** @Inject */
	public $db;
	
	/** @Inject */
	public $playfieldController;
	
	/** @Inject */
	public $towerController;
	
	/** @Logger */
	public $logger;
	
	const FACTION = "Neutral";
	
	/**
	 * @Setup
	 */
	public function setup() {
		
	}

	/**
	 * @HandlesCommand("planters")
	 * @Matches("/^planters ([0-9a-z]+) ([0-9]+)$/i")
	 */
	public function plantersCommandCommand($message, $channel, $sender, $sendto, $args) {
		$playfield_name = $args[1];
		$site_number = $args[2];
		
		$playfield = $this->playfieldController->get_playfield_by_name($playfield_name);
		if ($playfield === null) {
			$msg = "Invalid playfield.";
			$sendto->reply($msg);
			return;
		}
		
		$towerInfo = $this->towerController->get_tower_info($playfield->id, $site_number);
		if ($towerInfo === null) {
			$msg = "Invalid site number.";
			$sendto->reply($msg);
			return;
		}

		$types = array("I" => "1-33", "II" => "34-81", "III" => "82-128", "IV" => "129-176", "V" => "177-200", "VI" => "201-225", "VII" => "226-255");
		
		$blob = "Short name: <highlight>{$playfield->short_name} {$site_number}<end>\n";
		$blob .= "Level range: <highlight>{$towerInfo->min_ql}-{$towerInfo->max_ql}<end>\n";
		forEach ($types as $type => $range) {
			if ($this->isTowerType($towerInfo->min_ql, $towerInfo->max_ql, $type)) {
				$sql = "
					SELECT
						guild_name,
						faction,
						(SELECT
								guild_id
							FROM
								players
							WHERE
								guild = t.guild_name
								AND guild_id != 0
							ORDER BY
								last_update DESC
							LIMIT 1) AS guild_id
					FROM
						(SELECT
							guild_name,
							faction,
							CASE
								WHEN ct_ql >= 1 AND ct_ql < 34 THEN 1
							END as type_I,
							CASE
								WHEN ct_ql >= 34 AND ct_ql < 82 THEN 1
							END as type_II,
							CASE
								WHEN ct_ql >= 82 AND ct_ql < 129 THEN 1
							END as type_III,
							CASE
								WHEN ct_ql >= 129 AND ct_ql < 177 THEN 1
							END as type_IV,
							CASE
								WHEN ct_ql >= 177 AND ct_ql < 201 THEN 1
							END as type_V,
							CASE
								WHEN ct_ql >= 201 AND ct_ql < 226 THEN 1
							END as type_VI,
							CASE
								WHEN ct_ql >= 226 AND ct_ql < 276 THEN 1
							END as type_VII,
							CASE
								WHEN ct_ql >= 276 THEN 1
							END as type_VIII
						FROM
							scout_info s
						WHERE
							s.is_current = 1
							AND faction = ?) t
					GROUP BY
						guild_name,
						faction
					HAVING
						COUNT(type_$type) = 0
					ORDER BY
						guild_name
					";
				$data = $this->db->query($sql, self::FACTION);
				
				if (count($data) > 0) {
					$blob .= "\n<header2>Type $type ($range)<end>\n";
					forEach ($data as $row) {
						$orglistLink = '';
						if (!empty($row->guild_id)) {
							$orglistLink = $this->text->make_chatcmd("Online Org Members", "/tell <myname> orglist $row->guild_id");
						}
						$blob .= "$row->guild_name $orglistLink\n";
					}
				}
			}
		}
		
		if (empty($blob)) {
			$msg = "There are no orgs that can plant <highlight>$playfield->short_name $site_number<end>.";
		} else {
			$msg = $this->text->make_blob(self::FACTION . " Orgs eligble to plant $playfield->short_name $site_number", $blob);
		}
		$sendto->reply($msg);
	}
	
	public function isTowerType($lowql, $highql, $type) {
		switch ($type) {
			case "I":
				return $lowql < 34;				
				break;
			case "II":
				return $highql >= 34 && $lowql < 82;
				break;
			case "III":
				return $highql >= 82 && $lowql < 129;
				break;
			case "IV":
				return $highql >= 129 && $lowql < 177;
				break;
			case "V":
				return $highql >= 177 && $lowql < 201;
				break;
			case "VI":
				return $highql >= 201 && $lowql < 226;
				break;
			case "VII":
				return $highql >= 226 && $lowql < 276;
				break;
			case "VIII":
				return $highql >= 276;
				break;
		}
	}
}
