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
if(preg_match ("/^bossloot (.+)$/i", $message, $arr)) {

	$search = $arr[1];
	$search = ucwords(strtolower($search));
	$boss = '';
	
	$db->query("SELECT * FROM boss_lootdb WHERE itemname LIKE '%".str_replace("'", "''", $search)."%'");
	$loot_found = $db->numrows();

	if ($loot_found != 0) {
		//Find loot item and associated boss and his location
		$db->query("SELECT * FROM boss_lootdb, boss_namedb, whereis WHERE boss_lootdb.itemname LIKE '%".str_replace("'", "''", $search)."%' AND boss_namedb.bossid = boss_lootdb.bossid AND whereis.name = boss_namedb.bossname");
		$data = $db->fobject("all");
		forEach ($data as $row) {
			$bossname = $row->bossname;
			$bossid = $row->bossid;
			$where = $row->answer;
			//output Bossname once
			while ($oldbossname != $bossname) {
				$boss .= "\n\n<a href='chatcmd:///tell <myname> !boss $bossname'>$bossname</a>\n";
				$oldbossname = $bossname;
				$boss .= "<green>Can be found $where<end>\nDrops:";
				//output bossloot as many times as necessary
				$db->query("SELECT * FROM boss_lootdb, aodb WHERE boss_lootdb.itemname LIKE '%".str_replace("'", "''", $search)."%' AND boss_lootdb.bossid = $bossid AND boss_lootdb.itemid = aodb.lowid");
				$data = $db->fobject("all");
				forEach ($data as $row) {
					$lowid = $row->lowid;
					$highid = $row->highid;
					$ql = $row->highql;
					$loot_name = $row->itemname;
					$boss .= "<a href='itemref://$lowid/$highid/$ql.'>$loot_name</a> ";
				}
			}
		}
		$output = Text::makeBlob("Mobs that drop $search", $boss);
	} else {
		$output .= "<yellow>There were no matches for your search.</end>";
	}
	
	$this->send($output, $sendto);
} else {
	$syntax_error = true;
}

?>





