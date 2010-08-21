<?php
   /*
   Bossloot Module Ver 1.1
   Written By Jaqueme
   For Budabot
   Database Adapted From One Originally
   Compiled by Malosar For BeBot
   Boss Drop Table Database Module
   Written 5/11/07
   Last Modified 5/14/07
   */

$output = '';
if (preg_match ("/^boss (.+)$/i", $message, $arr)) {

	$search = $arr[1];
	$search = ucwords(strtolower($search));
	
	$boss = '';
	
	// Find bossname or Boss key
	$db->query("SELECT * FROM boss_namedb WHERE bossname LIKE '%".str_replace("'", "''", $search)."%' OR keyname LIKE '%".str_replace("'", "''", $search)."%'");
	$name_found = $db->numrows();
	
	//If multiple matches found output list of bosses
	if ($name_found > 1) {
		$bosses = $db->query("SELECT * FROM boss_namedb WHERE bossname LIKE '%".str_replace("'", "''", $search)."%' OR keyname LIKE '%".str_replace("'", "''", $search)."%'");
		forEach ($bosses as $row) {
			$bossname = $row->bossname;
			$bossid = $row->bossid;
			$locations = $db->query("SELECT * FROM whereis WHERE name = '".str_replace("'", "''", $bossname)."'");
			forEach ($locations as $row) {
				$bossname = $row->name;
				$boss .= "\n\n<a href='chatcmd:///tell <myname> !boss $bossname'>$bossname</a>\n";
				$where = $row->answer;
				$boss .= "<green>Can be found $where<end>\nDrops:";
				$bossloot = $db->query("SELECT * FROM boss_lootdb, aodb WHERE boss_lootdb.bossid = '$bossid' AND boss_lootdb.itemid = aodb.lowid");
				forEach ($bossloot as $row) {
					$lowid = $row->lowid;
					$highid = $row->highid;
					$ql = $row->highql;
					$loot_name = $row->itemname;
					$boss .= "<a href='itemref://$lowid/$highid/$ql.'>$loot_name</a> ";
				}
			}
		}
		$output = Text::makeBlob("Boss", $boss);
	} else if ($name_found  == 1) { //If single match found, output full loot table
		$row = $db->query("SELECT * FROM boss_namedb WHERE bossname LIKE  '%".str_replace("'", "''", $search)."%' OR keyname LIKE '%".str_replace("'", "''", $search)."%'", true);
		$name_id = $row->bossid;
		$name = $row->bossname;
		
		$boss .= "<yellow>$name\n\n";
		
		$data = $db->query("SELECT answer FROM whereis WHERE name = '".str_replace("'", "''", $name)."'");
		forEach ($data as $row) {
			$where = $row->answer;
			
			$boss .= "<green>Can be found $where<end>\n\n";
			$boss .= "Loot:\n\n";
		}
		
		$data = $db->query("SELECT * FROM boss_lootdb, aodb WHERE boss_lootdb.bossid = $name_id AND boss_lootdb.itemid = aodb.lowid");
		forEach ($data as $row) {
			$loid = $row->itemid;
			$hiid = $row->highid;
			$ql = $row->highql;
			$loot_name = $row->itemname;
			$icon = $row->icon;
		
			$boss .= "<img src=rdb://".$icon.">\n";
			$boss .= "<a href='itemref://$loid/$hiid/$ql.'>$loot_name</a>\n\n";
		}
		$output = Text::makeBlob("Results of Search for $search", $boss);
	} else {
		$output .= "<yellow>There were no matches for your search.</end>";
	}
	
	$chatBot->send($output, $sendto);
} else {
	$syntax_error = true;
}

?>