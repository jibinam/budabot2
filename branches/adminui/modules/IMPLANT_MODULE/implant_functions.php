<?php

// premade implant functions
function searchByProfession($profession) {
	$chatBot = Registry::getInstance('chatBot');
	$db = Registry::getInstance('db');

	$sql = "SELECT * FROM premade_implant WHERE profession = ? ORDER BY slot";
	return $db->query($sql, $profession);
}

function searchBySlot($slot) {
	$chatBot = Registry::getInstance('chatBot');
	$db = Registry::getInstance('db');

	$sql = "SELECT * FROM premade_implant WHERE slot = ? ORDER BY shiny, bright, faded";
	return $db->query($sql, $slot);
}

function searchByModifier($modifier) {
	$chatBot = Registry::getInstance('chatBot');
	$db = Registry::getInstance('db');

	$sql = "SELECT * FROM premade_implant WHERE shiny LIKE ? OR bright LIKE ? OR faded LIKE ?";
	return $db->query($sql, "%{$modifier}%", "%{$modifier}%", "%{$modifier}%");
}

function formatResults($implants) {
	$count = 0;
	forEach ($implants as $implant) {
		$blob .= getFormattedLine($implant);
		$count++;
	}
	
	return $blob;
}

function getFormattedLine($implant) {
	return "<green>$implant->profession<end> $implant->slot <white>$implant->ability<end> <font color='#FFFF00'>$implant->shiny</font> <font color='#FFA020'>$implant->bright</font> <font color='#FF8040'>$implant->faded</font>\n";
}

// implant functions
function getRequirements($ql) {
	$chatBot = Registry::getInstance('chatBot');
	$db = Registry::getInstance('db');

	$sql = "SELECT * FROM implant_requirements WHERE ql = ?";

	$row = $db->queryRow($sql, $ql);

	add_info($row);

	return $row;
}

function findMaxImplantQlByReqs($ability, $treatment) {
	$chatBot = Registry::getInstance('chatBot');
	$db = Registry::getInstance('db');

	$sql = "SELECT * FROM implant_requirements WHERE ability <= ? AND treatment <= ? ORDER BY ql DESC LIMIT 1";

	$row = $db->queryRow($sql, $ability, $treatment);
	
	add_info($row);

	return $row;
}

function formatClusterBonuses(&$obj) {
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

function add_info(&$obj) {
	if ($obj === null) {
		return;
	}
	
	_setHighestAndLowestQls($obj, 'abilityShiny');
	_setHighestAndLowestQls($obj, 'abilityBright');
	_setHighestAndLowestQls($obj, 'abilityFaded');
	_setHighestAndLowestQls($obj, 'skillShiny');
	_setHighestAndLowestQls($obj, 'skillBright');
	_setHighestAndLowestQls($obj, 'skillFaded');
	
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

function _setHighestAndLowestQls(&$obj, $var) {
	$chatBot = Registry::getInstance('chatBot');
	$db = Registry::getInstance('db');

	$varValue = $obj->$var;

	$sql = "SELECT MAX(ql) as max, MIN(ql) as min FROM implant_requirements WHERE $var = ?";
	$row = $db->queryRow($sql, $varValue);

	// camel case var name
	$tempNameVar = ucfirst($var);
	$tempHighestName = "highest$tempNameVar";
	$tempLowestName = "lowest$tempNameVar";

	$obj->$tempLowestName = $row->min;
	$obj->$tempHighestName = $row->max;
}

?>
