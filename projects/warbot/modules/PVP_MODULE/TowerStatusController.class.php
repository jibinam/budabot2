<?php

/**
 * Authors: 
 *	- Tyrence (RK2)
 *
 * @Instance
 *
 * Commands this controller contains:
 *	@DefineCommand(
 *		command     = 'towerstatus', 
 *		accessLevel = 'all', 
 *		description = 'Show various stats about current tower site ownership', 
 *		help        = 'towerstatus.txt'
 *	)
 */
class TowerStatusController {

	/**
	 * Name of the module.
	 * Set automatically by module loader.
	 */
	public $moduleName;

	/** @Inject */
	public $text;
	
	/** @Inject */
	public $db;
	
	/** @Logger */
	public $logger;

	/**
	 * @HandlesCommand("towerstatus")
	 * @Matches("/^towerstatus$/i")
	 */
	public function towerstatusCommand($message, $channel, $sender, $sendto, $args) {
		$sql = "
			SELECT
				faction,
				COUNT(*) cnt,
				AVG(ct_ql) avg_ct_ql,
				COUNT(DISTINCT guild_name) num_guilds,
				COUNT(type_I) num_type_I,
				COUNT(type_II) num_type_II,
				COUNT(type_III) num_type_III,
				COUNT(type_IV) num_type_IV,
				COUNT(type_V) num_type_V,
				COUNT(type_VI) num_type_VI,
				COUNT(type_VII) num_type_VII
			FROM
				(SELECT
					*,
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
					scout_info
				WHERE
					is_current = 1) t
			GROUP BY
				faction
			ORDER BY
				faction";
		$data = $this->db->query($sql);
		$totalRow = $this->getTotalRow();
		
		$blob .= '';
		forEach ($data as $row) {
			$blob .= "\n<$row->faction>$row->faction (Side XP " . sprintf("%01.2f", $row->cnt / $totalRow->cnt * 25) . "%)<end>\n";
			$blob .= "Number of sites: <highlight>" . round($row->cnt / $totalRow->cnt, 2) * 100 . "%<end> ($row->cnt)\n";
			$blob .= "Average CT QL: <highlight>" . round($row->avg_ct_ql) . "<end>\n";
			$blob .= "Number of orgs: <highlight>$row->num_guilds<end>\n";
			$blob .= "Average number of sites per org: <highlight>" . round($row->cnt / $row->num_guilds, 1) . "<end>\n";
			$blob .= "Type I sites: <highlight>" . round($row->num_type_I / $totalRow->num_type_I, 2) * 100 . "%<end> ($row->num_type_I)\n";
			$blob .= "Type II sites: <highlight>" . round($row->num_type_II / $totalRow->num_type_II, 2) * 100 . "%<end> ($row->num_type_II)\n";
			$blob .= "Type III sites: <highlight>" . round($row->num_type_III / $totalRow->num_type_III, 2) * 100 . "%<end> ($row->num_type_III)\n";
			$blob .= "Type IV sites: <highlight>" . round($row->num_type_IV / $totalRow->num_type_IV, 2) * 100 . "%<end> ($row->num_type_IV)\n";
			$blob .= "Type V sites: <highlight>" . round($row->num_type_V / $totalRow->num_type_V, 2) * 100 . "%<end> ($row->num_type_V)\n";
			$blob .= "Type VI sites: <highlight>" . round($row->num_type_VI / $totalRow->num_type_VI, 2) * 100 . "%<end> ($row->num_type_VI)\n";
			$blob .= "Type VII sites: <highlight>" . round($row->num_type_VII / $totalRow->num_type_VII, 2) * 100 . "%<end> ($row->num_type_VII)\n";
		}
		$msg = $this->text->make_blob("Tower Status", $blob);
		$sendto->reply($msg);
	}
	
	public function getTotalRow() {
		$sql = "
			SELECT
				faction,
				COUNT(*) cnt,
				AVG(ct_ql) avg_ct_ql,
				COUNT(DISTINCT guild_name) num_guilds,
				COUNT(type_I) num_type_I,
				COUNT(type_II) num_type_II,
				COUNT(type_III) num_type_III,
				COUNT(type_IV) num_type_IV,
				COUNT(type_V) num_type_V,
				COUNT(type_VI) num_type_VI,
				COUNT(type_VII) num_type_VII
			FROM
				(SELECT
					*,
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
					scout_info
				WHERE
					is_current = 1) t";
		return $this->db->queryRow($sql);
	}
}
