<?php

/**
 * Authors: 
 *  - Tyrence (RK2)
 *	- Imoutochan (RK1)
 *
 * @Instance
 *
 * Commands this class contains:
 *	@DefineCommand(
 *		command     = 'implant',
 *		accessLevel = 'all',
 *		description = 'Shows info about implants given a ql or stats',
 *		help        = 'implant.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'premade',
 *		accessLevel = 'all',
 *		description = 'Searches for implants out of the premade implants booths',
 *		help        = 'premade.txt'
 *	)
 *	@DefineCommand(
 *		command     = 'cluster',
 *		accessLevel = 'all',
 *		description = 'Find which clusters buff a specified skill',
 *		help        = 'cluster.txt'
 *	)
 */
class ImplantController {

	/**
	 * Name of the module.
	 * Set automatically by module loader.
	 */
	public $moduleName;
	
	/** @Inject */
	public $chatBot;
	
	/** @Inject */
	public $db;

	/** @Inject */
	public $text;

	/** @Inject */
	public $util;
	
	/**
	 * @Setup
	 */
	public function setup() {
		$this->db->loadSQLFile($this->moduleName, "implant_requirements");
		$this->db->loadSQLFile($this->moduleName, "premade_implant");
		$this->db->loadSQLFile($this->moduleName, "cluster");
	}
	
	/**
	 * @HandlesCommand("implant")
	 * @Matches("/^implant ([0-9]+)$/i")
	 */
	public function implantQlCommand($message, $channel, $sender, $sendto, $args) {
		$ql = $args[1];

		// make sure the $ql is an integer between 1 and 300
		if (($ql < 1) || ($ql > 300)) {
			$msg = "You must enter a value between 1 and 300.";
		} else {
			$obj = $this->getRequirements($ql);
			$clusterInfo = $this->formatClusterBonuses($obj);
			$link = $this->text->make_blob('More info', $clusterInfo, "Implant Info (ql $obj->ql)");
			$msg = "QL $ql implants--Ability: {$obj->ability}, Treatment: {$obj->treatment} $link";
		}

		$sendto->reply($msg);
	}

	/**
	 * @HandlesCommand("implant")
	 * @Matches("/^implant ([0-9]+) ([0-9]+)$/i")
	 */
	public function implantRequirementsCommand($message, $channel, $sender, $sendto, $args) {
		$ability = $args[1];
		$treatment = $args[2];

		if ($treatment < 11 || $ability < 6) {
			$msg = "You do not have enough treatment or ability to wear an implant.";
		} else {
			$obj = $this->findMaxImplantQlByReqs($ability, $treatment);
			$clusterInfo = $this->formatClusterBonuses($obj);
			$link = $this->text->make_blob("ql $obj->ql", $clusterInfo, "Implant Info (ql $obj->ql)");

			$msg = "The highest ql implant you can wear is $link which requires <highlight>$obj->treatment Treatment<end> and <highlight>$obj->ability Ability<end>.";
		}
		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("premade")
	 * @Matches("/^premade (.*)$/i")
	 */
	public function premadeCommand($message, $channel, $sender, $sendto, $args) {
		$searchTerms = strtolower($args[1]);
		$results = null;

		$profession = $this->util->get_profession_name($searchTerms);
		if ($profession != '') {
			$searchTerms = $profession;
			$results = $this->searchByProfession($profession);
		} else if ($searchTerms == 'head' || $searchTerms == 'eye' || $searchTerms == 'ear' || $searchTerms == 'rarm' ||
			$searchTerms == 'chest' || $searchTerms == 'larm' || $searchTerms == 'rwrist' || $searchTerms == 'waist' ||
			$searchTerms == 'lwrist' || $searchTerms == 'rhand' || $searchTerms == 'legs' || $searchTerms == 'lhand' ||
			$searchTerms == 'feet') {

			$results = $this->searchBySlot($searchTerms);
		} else {
			$results = $this->searchByModifier($searchTerms);
		}

		if ($results != null) {
			$blob = $this->formatResults($results);
			$blob .= "\n\nWritten by Tyrence (RK2)";
			$msg = $this->text->make_blob("Implant Search Results for '$searchTerms'", $blob);
		} else {
			$msg = "No results found.";
		}

		$sendto->reply($msg);
	}
	
	/**
	 * @HandlesCommand("cluster")
	 * @Matches("/^cluster (.+)$/i")
	 */
	public function clusterCommand($message, $channel, $sender, $sendto, $args) {
		$name = trim($args[1]);

		$info = "";
		$sql = "SELECT * FROM cluster WHERE skill LIKE ?";
		$results = $this->db->query($sql, '%' . str_replace(' ', '%', $name) . '%');
		$count = count($results);

		if ($count == 0) {
			$sendto->reply("No matches found.");
		} else if ($count == 1) {
			$row = $results[0];
			$msg = "<u>$row->skill Cluster</u>:\n<tab><font color=#ffcc33>Shiny</font>: ".$row->shiny.
					 "<tab><font color=#ffff55>Bright</font>: ".$row->bright.
					 "<tab><font color=#FFFF99>Faded</font>: ".$row->faded;
			$sendto->reply($msg);
		} else {
			forEach ($results as $row) {
				$info .= "<pagebreak>";
				$info .= "<u>$row->skill Cluster</u>:\n<tab><font color=#ffcc33>Shiny</font>: ".$row->shiny.
						 "<tab><font color=#ffff55>Bright</font>: ".$row->bright.
						 "<tab><font color=#FFFF99>Faded</font>: ".$row->faded;
				$info .= "\n\n";
			}
		
			$inside = "Your query of <highlight>$name<end> returned the following results:\n\n";
			$inside .= $info;
			$inside .= "\n\nby Imoutochan (RK1)";

			$windowlink = $this->text->make_blob("Cluster search results ($count)", $inside);
			$sendto->reply($windowlink);
		}
	}
	
	// premade implant functions
	public function searchByProfession($profession) {
		$sql = "SELECT * FROM premade_implant WHERE profession = ? ORDER BY slot";
		return $this->db->query($sql, $profession);
	}

	public function searchBySlot($slot) {
		$sql = "SELECT * FROM premade_implant WHERE slot = ? ORDER BY shiny, bright, faded";
		return $this->db->query($sql, $slot);
	}

	public function searchByModifier($modifier) {
		$dbparam = '%' . str_replace(' ', '%', $modifier) . '%';
		$sql = "SELECT * FROM premade_implant WHERE shiny LIKE ? OR bright LIKE ? OR faded LIKE ?";
		return $this->db->query($sql, $dbparam, $dbparam, $dbparam);
	}

	public function formatResults($implants) {
		$count = 0;
		forEach ($implants as $implant) {
			$blob .= $this->getFormattedLine($implant);
			$count++;
		}

		return $blob;
	}

	public function getFormattedLine($implant) {
		return "<green>$implant->profession<end> $implant->slot <white>$implant->ability<end> <font color='#FFFF00'>$implant->shiny</font> <font color='#FFA020'>$implant->bright</font> <font color='#FF8040'>$implant->faded</font>\n";
	}

	// implant functions
	public function getRequirements($ql) {
		$sql = "SELECT * FROM implant_requirements WHERE ql = ?";

		$row = $this->db->queryRow($sql, $ql);

		$this->add_info($row);

		return $row;
	}

	public function findMaxImplantQlByReqs($ability, $treatment) {
		$sql = "SELECT * FROM implant_requirements WHERE ability <= ? AND treatment <= ? ORDER BY ql DESC LIMIT 1";

		$row = $this->db->queryRow($sql, $ability, $treatment);

		$this->add_info($row);

		return $row;
	}

	public function formatClusterBonuses(&$obj) {
		$msg = "For ql $obj->ql clusters,\n\n";

		$msg .= "You will gain for most skills:\n" .
			"<tab>Shiny    $obj->skillShiny ($obj->lowestSkillShiny - $obj->highestSkillShiny)\n" .
			"<tab>Bright    $obj->skillBright ($obj->lowestSkillBright - $obj->highestSkillBright)\n" .
			"<tab>Faded   $obj->skillFaded ($obj->lowestSkillFaded - $obj->highestSkillFaded)\n" .
			"-----------------------\n" .
			"<tab>Total   $obj->skillTotal\n";

		$msg .= "\n\n";

		$msg .= "You will gain for abilities:\n" .
			"<tab>Shiny    $obj->abilityShiny ($obj->lowestAbilityShiny - $obj->highestAbilityShiny)\n" .
			"<tab>Bright    $obj->abilityBright ($obj->lowestAbilityBright - $obj->highestAbilityBright)\n" .
			"<tab>Faded   $obj->abilityFaded ($obj->lowestAbilityFaded - $obj->highestAbilityFaded)\n" .
			"-----------------------\n" .
			"<tab>Total   $obj->abilityTotal\n";


		if ($obj->ql > 250) {

			$msg .= "\n\nRequires Title Level 6";

		} else if ($obj->ql > 200) {

			$msg .= "\n\nRequires Title Level 5";
		}

		$msg .= "\n\nMinimum ql for clusters:\n\n" .
			"<tab>Shiny: $obj->minShinyClusterQl\n" .
			"<tab>Bright: $obj->minBrightClusterQl\n" .
			"<tab>Faded: $obj->minFadedClusterQl\n";

		$msg .= "\n\nWritten by Tyrence (RK2)";

		return $msg;
	}

	public function add_info(&$obj) {
		if ($obj === null) {
			return;
		}

		$this->_setHighestAndLowestQls($obj, 'abilityShiny');
		$this->_setHighestAndLowestQls($obj, 'abilityBright');
		$this->_setHighestAndLowestQls($obj, 'abilityFaded');
		$this->_setHighestAndLowestQls($obj, 'skillShiny');
		$this->_setHighestAndLowestQls($obj, 'skillBright');
		$this->_setHighestAndLowestQls($obj, 'skillFaded');

		$obj->abilityTotal = $obj->abilityShiny + $obj->abilityBright + $obj->abilityFaded;
		$obj->skillTotal = $obj->skillShiny + $obj->skillBright + $obj->skillFaded;

		$obj->minShinyClusterQl = round($obj->ql * 0.86);
		$obj->minBrightClusterQl = round($obj->ql * 0.84);
		$obj->minFadedClusterQl = round($obj->ql * 0.82);

		// if implant ql is 201+, then clusters must be refined and must be ql 201+ also
		if ($obj->ql >= 201) {

			if ($obj->minShinyClusterQl < 201) {
				$obj->minShinyClusterQl = 201;
			}
			if ($obj->minBrightClusterQl < 201) {
				$obj->minBrightClusterQl = 201;
			}
			if ($obj->minFadedClusterQl < 201) {
				$obj->minFadedClusterQl = 201;
			}
		}
	}

	public function _setHighestAndLowestQls(&$obj, $var) {
		$varValue = $obj->$var;

		$sql = "SELECT MAX(ql) as max, MIN(ql) as min FROM implant_requirements WHERE $var = ?";
		$row = $this->db->queryRow($sql, $varValue);

		// camel case var name
		$tempNameVar = ucfirst($var);
		$tempHighestName = "highest$tempNameVar";
		$tempLowestName = "lowest$tempNameVar";

		$obj->$tempLowestName = $row->min;
		$obj->$tempHighestName = $row->max;
	}
}

?>
