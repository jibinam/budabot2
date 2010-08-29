<?PHP
   /*
   Spirits Module Ver 1.1
   Written By Jaqueme
   For Budabot
   Database Adapted From One Originally
   Compiled by Wolfbiter For BeBot
   Spirits Database Module
   Written 5/11/07
   Last Modified 5/27/07
   */

	//If searched by Name or Slot
if (preg_match("/^spirits ([^0-9,]+)$/i", $message, $arr)) {
	$name = $arr[1];
	$name = ucwords(strtolower($name));
	$title = "Search Spirits Database for $name";
	$data = $db->query("SELECT * FROM spiritsdb WHERE name LIKE '%".str_replace("'", "''", $name)."%' OR spot LIKE '%".str_replace("'", "''", $name)."%' ORDER BY level");
	$matches= $db->numrows();
	if ($matches == 0) {
		$spirits .="<red>There were no matches found for $name.\nTry putting a comma between search values.\n\n";
		$spirits .="Valid slot types are:\n";
		$spirits .="Head\n";
		$spirits .="Eye\n";
		$spirits .="Ear\n";
		$spirits .="Chest\n";
		$spirits .="Larm\n";
		$spirits .="Rarm\n";
		$spirits .="Waist\n";
		$spirits .="Lwrist\n";
		$spirits .="Rwrist\n";
		$spirits .="Legs\n";
		$spirits .="Lhand\n";
		$spirits .="Rhand\n";
		$spirits .="Feet\n";
	} else {
		forEach ($data as $row) {
			$name = $row->name;
			$spot = $row->spot;
			$lvl = $row->level;
			$loid = $row->id;
			$agi = $row->agility;
			
			$data = $db->query("SELECT * FROM aodb WHERE lowid = '$loid'");
			forEach ($data as $row); {
				$hiid = $row->highid;
				$icon = $row->icon;
				$phat = $row->name;
				$hiql = $row->highql;
			}
			$spirits .= "<img src=rdb://".$icon."> ";
			$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
			$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility/Sense Needed=$agi<end>\n\n";
		}
	}
}
	//If searched by name and slot
else if (preg_match("/^spirits ([^0-9]+),([^0-9]+)$/i", $message, $arr)) {
	if (preg_match("/(chest|ear|eye|feet|head|larm|legs|lhand|lwrist|rarm|rhand|rwrist|waist)/i", $arr[1])) {
		$slot = $arr[1];
		$name = $arr[2];
		$title = "Search Spirits Database for $name $slot";
	} else if (preg_match("/(chest|ear|eye|feet|head|larm|legs|lhand|lwrist|rarm|rhand|rwrist|waist)/i", $arr[2])) {
		$name = $arr[1];
		$slot = $arr[2];
		$title = "Search Spirits Database for $name $slot";
	} else {
		$title = "Search Spirits Database Error";
		$spirits .= "<red>No matches were found for $name $slot\n\n";
		$spirits .="If searching by Spirit Name and Slot valid slot types are:\n";
		$spirits .="Head\n";
		$spirits .="Eye\n";
		$spirits .="Ear\n";
		$spirits .="Chest\n";
		$spirits .="Larm\n";
		$spirits .="Rarm\n";
		$spirits .="Waist\n";
		$spirits .="Lwrist\n";
		$spirits .="Rwrist\n";
		$spirits .="Legs\n";
		$spirits .="Lhand\n";
		$spirits .="Rhand\n";
		$spirits .="Feet\n";
	}
	$name = ucwords(strtolower($name));
	$name = trim($name);
	$slot = ucwords(strtolower($slot));
	$slot = trim($slot);
	$data = $db->query("SELECT * FROM spiritsdb WHERE name LIKE '%".str_replace("'", "''", $name)."%' AND spot = '$slot' ORDER BY level");
	$matches= $db->numrows();
	if ($matches == 0) {
		$spirits .= "<red>No matches found for $name $slot";
	}
	forEach ($data as $row) {
		$name = $row->name;
		$spot = $row->spot;
		$lvl = $row->level;
		$loid = $row->id;
		$agi = $row->agility;
		
		$data2 = $db->query("SELECT * FROM aodb WHERE lowid = '$loid'");
		forEach ($data2 as $row); {
			$hiid = $row->highid;
			$icon = $row->icon;
			$phat = $row->name;
			$hiql = $row->highql;
		}
		$spirits .= "<img src=rdb://".$icon."> ";
		$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
		$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility/Sense Needed=$agi<end>\n\n";
	}
}
	// If searched by ql
else if (preg_match("/^spirits ([0-9]+)$/i", $message, $arr)) {
	$ql = $arr[1];
    if ($ql <= 1 OR $ql >= 300) {
        $msg = "<red>No valid Ql specified(1-300)";
		$chatBot->send($msg, $sendto);
        return;
    }
	$title = "Search for Spirits QL $ql";
	$data = $db->query("SELECT * FROM spiritsdb where ql = $ql ORDER BY ql");
	forEach ($data as $row) {
		$name = $row->name;
		$spot = $row->spot;
		$lvl = $row->level;
		$loid = $row->id;
		$agi = $row->agility;
		
		$data2 = $db->query("SELECT * FROM aodb WHERE lowid = $loid");
		forEach ($data2 as $row); {
			$hiid = $row->highid;
			$icon = $row->icon;
			$phat = $row->name;
			$hiql = $row->highql;
		}
		$spirits .= "<img src=rdb://".$icon."> ";
		$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
		$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility/Sense Needed=$agi<end>\n\n";
	}
}
	// If searched by ql range
else if (preg_match("/^spirits ([0-9]+)-([0-9]+)$/i", $message, $arr)) {
	$qllorange = $arr[1];
	$qlhirange = $arr[2];
	if ($qllorange < 1 OR $qlhirange > 219 OR $qllorange >= $qlhirange) {
		$msg = "<red>Invalid Ql range specified(1-219)";
        $chatBot->send($msg, $sendto);
        return;
	}
	$title = "Search for Spirits QL $qllorange to $qlhirange";
	$data = $db->query("SELECT * FROM spiritsdb where ql >= $qllorange AND ql <= $qlhirange ORDER BY ql");
	forEach ($data as $row) {
		$name = $row->name;
		$spot = $row->spot;
		$lvl = $row->level;
		$loid = $row->id;
		$agi = $row->agility;
		
		$data2 = $db->query("SELECT * FROM aodb WHERE lowid = $loid");
		forEach ($data2 as $row); {
			$hiid = $row->highid;
			$icon = $row->icon;
			$phat = $row->name;
			$hiql = $row->highql;
		}
		$spirits .= "<img src=rdb://".$icon."> ";
		$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
		$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility/Sense Needed=$agi<end>\n\n";
	}
}
	// If searched by ql and slot
else if (preg_match("/^spirits ([0-9]+) (.+)$/i", $message, $arr)) {
	$ql = $arr[1];
	$name = $arr[2];
	$name = ucwords(strtolower($name));
    if ($ql < 1 OR $ql > 300) {
        $msg = "<red>No valid Ql specified(1-300)";
        $chatBot->send($msg, $sendto);
        return;
    } else if (preg_match("/[^chest|ear|eye|feet|head|larm|legs|lhand|lwrist|rarm|rhand|rwrist|waist]/i", $name)) {
		$title = "Search Spirits Database Error";
		$spirits .= "<red>Invalid Input\n\n";
		$spirits .="If searching by Ql and Slot valid slot types are:\n";
		$spirits .="Head\n";
		$spirits .="Eye\n";
		$spirits .="Ear\n";
		$spirits .="Chest\n";
		$spirits .="Larm\n";
		$spirits .="Rarm\n";
		$spirits .="Waist\n";
		$spirits .="Lwrist\n";
		$spirits .="Rwrist\n";
		$spirits .="Legs\n";
		$spirits .="Lhand\n";
		$spirits .="Rhand\n";
		$spirits .="Feet\n";
	} else  {
		$title = "Search for $name Spirits QL $ql";
		$data = $db->query("SELECT * FROM spiritsdb where spot = '".str_replace("'", "''", $name)."' AND ql = $ql ORDER BY ql");
		$matches = $db->numrows();
		if ($matches == 0) {
			$spirits .="<red>There were no matches for Ql $ql $name";
		}
		forEach ($data as $row) {
			$name = $row->name;
			$spot = $row->spot;
			$lvl = $row->level;
			$loid = $row->id;
			$agi = $row->agility;
			
			$data2 = $db->query("SELECT * FROM aodb WHERE lowid = $loid");
			forEach ($data2 as $row); {
				$hiid = $row->highid;
				$icon = $row->icon;
				$phat = $row->name;
				$hiql = $row->highql;
			}
			$spirits .= "<img src=rdb://".$icon."> ";
			$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
			$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility Needed=$agi<end>\n\n";
		}
	}
}
	// If searched by ql range and slot
else if (preg_match("/^spirits ([0-9]+)-([0-9]+) (.+)$/i", $message, $arr)) {
	$qllorange = $arr[1];
	$qlhirange = $arr[2];
	$name = $arr[3];
	$name = ucwords(strtolower($name));
	if ($qllorange < 1 OR $qlhirange > 300 OR $qllorange >= $qlhirange) {
		$msg = "<red>Invalid Ql range specified(1-300)";
		$chatBot->send($msg, $sendto);
		return;
    } else if (preg_match("/[^chest|ear|eye|feet|head|larm|legs|lhand|lwrist|rarm|rhand|rwrist|waist]/i",$name)) {
		$title = "Search Spirits Database <red>Error<end>";
		$spirits .= "<red>Invalid Input\n\n";
		$spirits .="If searching by QL Range and Slot valid slot types are:\n";
		$spirits .="Head\n";
		$spirits .="Eye\n";
		$spirits .="Ear\n";
		$spirits .="Chest\n";
		$spirits .="Larm\n";
		$spirits .="Rarm\n";
		$spirits .="Waist\n";
		$spirits .="Lwrist\n";
		$spirits .="Rwrist\n";
		$spirits .="Legs\n";
		$spirits .="Lhand\n";
		$spirits .="Rhand\n";
		$spirits .="Feet\n";
	} else {
		$title = "Search for $name Spirits QL $qllorange to $qlhirange";
		$data = $db->query("SELECT * FROM spiritsdb where spot = '".str_replace("'", "''", $name)."' AND ql >= $qllorange AND ql <= $qlhirange ORDER BY ql");
		$matches = $db->numrows();
		if ($matches == 0) {
			$spirits .="<red>There were no matches for Ql $qllorange-$qlhirange $name";
		}
		forEach ($data as $row) {
			$name = $row->name;
			$spot = $row->spot;
			$lvl = $row->level;
			$loid = $row->id;
			$agi = $row->agility;
			
			$data2 = $db->query("SELECT * FROM aodb WHERE lowid = $loid");
			forEach ($data2 as $row); {
				$hiid = $row->highid;
				$icon = $row->icon;
				$phat = $row->name;
				$hiql = $row->highql;
			}
			$spirits .= "<img src=rdb://".$icon."> ";
			$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
			$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility/Sense Needed=$agi<end>\n\n";
		}
	}
}
	// If searched by minimum level
else if (preg_match ("/^spiritslvl ([0-9]+)$/i", $message, $arr)) {
	$lvl = $arr[1];
    if ($lvl < 1 OR $lvl > 219) {
        $msg = "<red>No valid Level specified(1-219)";
        $chatBot->send($msg, $sendto);
        return;
    }
	$title = "Search for Spirits Level $lvl";
	$lolvl = $lvl-10;
	$data = $db->query("SELECT * FROM spiritsdb where level < $lvl AND level > $lolvl ORDER BY level");
	forEach ($data as $row) {
		$name = $row->name;
		$spot = $row->spot;
		$lvl = $row->level;
		$loid = $row->id;
		$agi = $row->agility;
		
		$data2 = $db->query("SELECT * FROM aodb WHERE lowid = $loid");
		forEach ($data2 as $row); {
			$hiid = $row->highid;
			$icon = $row->icon;
			$phat = $row->name;
			$hiql = $row->highql;
		}
		$spirits .= "<img src=rdb://".$icon."> ";
		$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
		$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility/Sense Needed=$agi<end>\n\n";
	}
}
	// If searched by minimum level range
else if (preg_match("/^spiritslvl ([0-9]+)-([0-9]+)$/i", $message, $arr)) {
	$lvllorange = $arr[1];
	$lvlhirange = $arr[2];
	if ($lvllorange < 1 OR $lvlhirange > 219 OR $lvllorange >= $lvlhirange) {
		$msg = "<red>Invalid Level range specified(1-219)";
        $chatBot->send($msg, $sendto);
        return;
	}
	$title = "Search for Spirits Level $lvllorange to $lvlhirange";
	$data = $db->query("SELECT * FROM spiritsdb where level >= $lvllorange AND level <= $lvlhirange ORDER BY level");
	forEach ($data as $row) {
		$name = $row->name;
		$spot = $row->spot;
		$lvl = $row->level;
		$loid = $row->id;
		$agi = $row->agility;
		
		$data2 = $db->query("SELECT * FROM aodb WHERE lowid = $loid");
		forEach ($data2 as $row); {
			$hiid = $row->highid;
			$icon = $row->icon;
			$phat = $row->name;
			$hiql = $row->highql;
		}
		$spirits .= "<img src=rdb://".$icon."> ";
		$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
		$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility/Sense Needed=$agi<end>\n\n";
	}
}
	// If searched by minimum level and slot
else if (ereg ("/^spiritslvl ([0-9]+) (.+)$/i", $message, $arr)) {
	$lvl = $arr[1];
	$name = $arr[2];
	$name = ucwords(strtolower($name));
    if ($lvl < 1 OR $lvl > 219) {
        $msg = "<red>No valid Level specified(1-219)";
        $chatBot->send($msg, $sendto);
        return;
    } else if (preg_match("/[^chest|ear|eye|feet|head|larm|legs|lhand|lwrist|rarm|rhand|rwrist|waist]/i",$name)) {
		$title = "Search Spirits Database <red>Error<end>";
		$spirits .= "<red>Invalid Input\n\n";
		$spirits .="If searching by Minimum Level and Slot valid slot types are:\n";
		$spirits .="Head\n";
		$spirits .="Eye\n";
		$spirits .="Ear\n";
		$spirits .="Chest\n";
		$spirits .="Larm\n";
		$spirits .="Rarm\n";
		$spirits .="Waist\n";
		$spirits .="Lwrist\n";
		$spirits .="Rwrist\n";
		$spirits .="Legs\n";
		$spirits .="Lhand\n";
		$spirits .="Rhand\n";
		$spirits .="Feet\n";
	} else {
		$title = "Search for $name Spirits Level $lvl";
		$lolvl = $lvl-10;
		$data = $db->query("SELECT * FROM spiritsdb where spot = '".str_replace("'", "''", $name)."' AND level < $lvl AND level > $lolvl ORDER BY level");
		$matches = $db->numrows();
		if ($matches == 0) {
			$spirits .="<red>There were no matches for Minimum Level $lvl $name";
		}
		forEach ($data as $row) {
			$name = $row->name;
			$spot = $row->spot;
			$lvl = $row->level;
			$loid = $row->id;
			$agi = $row->agility;
			
			$data2 = $db->query("SELECT * FROM aodb WHERE lowid = $loid");
			forEach ($data2 as $row); {
				$hiid = $row->highid;
				$icon = $row->icon;
				$phat = $row->name;
				$hiql = $row->highql;
			}
			$spirits .= "<img src=rdb://".$icon."> ";
			$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
			$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility/Sense Needed=$agi<end>\n\n";
		}
	}
}
	// If searched by minimum level range and slot
else if (preg_match("/^spiritslvl ([0-9]+)-([0-9]+) (.+)$/i", $message, $arr)) {
	$lvllorange = $arr[1];
	$lvlhirange = $arr[2];
	$name = $arr[3];
	$name = ucwords(strtolower($name));
	if ($lvllorange < 1 OR $lvlhirange > 219 OR $lvllorange >= $lvlhirange) {
		$msg = "<red>Invalid Level range specified(1-219)";
        $chatBot->send($msg, $sendto);
        return;
    } else if (preg_match("/[^chest|ear|eye|feet|head|larm|legs|lhand|lwrist|rarm|rhand|rwrist|waist]/i",$name)) {
		$title = "Search Spirits Database Error";
		$spirits .= "<red>Invalid Input\n\n";
		$spirits .="If searching by Minimum Level and Slot valid slot types are:\n";
		$spirits .="Head\n";
		$spirits .="Eye\n";
		$spirits .="Ear\n";
		$spirits .="Chest\n";
		$spirits .="Larm\n";
		$spirits .="Rarm\n";
		$spirits .="Waist\n";
		$spirits .="Lwrist\n";
		$spirits .="Rwrist\n";
		$spirits .="Legs\n";
		$spirits .="Lhand\n";
		$spirits .="Rhand\n";
		$spirits .="Feet\n";
	} else {
		$title = "Search for $name Spirits Level $lvllorange to $lvlhirange";
		$data = $db->query("SELECT * FROM spiritsdb where spot = '".str_replace("'", "''", $name)."' AND level >= $lvllorange AND level <= $lvlhirange ORDER BY level");
		$matches = $db->numrows();
		if ($matches == 0) {
			$spirits .="<red>There were no matches for Minimum Level $lvllorange-$lvlhirange $name";
		}
		forEach ($data as $row) {
			$name = $row->name;
			$spot = $row->spot;
			$lvl = $row->level;
			$loid = $row->id;
			$agi = $row->agility;
			
			$data2 = $db->query("SELECT * FROM aodb WHERE lowid = $loid");
			forEach ($data2 as $row); {
				$hiid = $row->highid;
				$icon = $row->icon;
				$phat = $row->name;
				$hiql = $row->highql;
			}
			$spirits .= "<img src=rdb://".$icon."> ";
			$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
			$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility/Sense Needed=$agi<end>\n\n";
		}
	}
}
	//Search by Agility
else if (preg_match ("/^spiritsagi ([0-9,]+)$/i", $message, $arr)) {
	$agility = $arr[1];
	$loagility = $agility - 10;
	$title = "Search Spirits Database for Agility Requirement of $agility";
	$data = $db->query("SELECT * FROM spiritsdb WHERE agility < $agility AND agility > $loagility ORDER BY level");
	$matches = $db->numrows();
	if ($matches == 0) {
		$spirits .="<red>There were no matches for Spirits with an Agility Requirement of $agility";
	}
	forEach ($data as $row) {
		$name = $row->name;
		$spot = $row->spot;
		$lvl = $row->level;
		$loid = $row->id;
		$agi = $row->agility;
		
		$data2 = $db->query("SELECT * FROM aodb WHERE lowid = '$loid'");
		forEach ($data2 as $row); {
			$hiid = $row->highid;
			$icon = $row->icon;
			$phat = $row->name;
			$hiql = $row->highql;
		}
		$spirits .= "<img src=rdb://".$icon."> ";
		$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
		$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility Needed=$agi<end>\n\n";
	}
}
	// If searched by Agility and slot
else if (preg_match ("/^spiritsagi ([0-9]+) (.+)$/i", $message, $arr)) {
	$agility = $arr[1];
	$loagility = $agility - 10;
	var_dump($loagility,$agility);
	$name = $arr[2];
	$name = ucwords(strtolower($name));
    if ($agility < 1 OR $agility > 1276) {
        $msg = "<red><red>No valid Agility specified(1-1276)";
        $chatBot->send($msg, $sendto);
        return;
    } else if (preg_match("/[^chest|ear|eye|feet|head|larm|legs|lhand|lwrist|rarm|rhand|rwrist|waist]/i",$name)) {
		$title = "Search Spirits Database Error";
		$spirits .= "<red>Invalid Input\n\n";
		$spirits .="If searching by Agility and Slot valid slot types are:\n";
		$spirits .="Head\n";
		$spirits .="Eye\n";
		$spirits .="Ear\n";
		$spirits .="Chest\n";
		$spirits .="Larm\n";
		$spirits .="Rarm\n";
		$spirits .="Waist\n";
		$spirits .="Lwrist\n";
		$spirits .="Rwrist\n";
		$spirits .="Legs\n";
		$spirits .="Lhand\n";
		$spirits .="Rhand\n";
		$spirits .="Feet\n";
	} else  {
		$title = "Search for $name Spirits With Agility Req of $agility";
		$data = $db->query("SELECT * FROM spiritsdb where spot = '".str_replace("'", "''", $name)."' AND agility < $agility AND agility > $loagility ORDER BY ql");
		$matches = $db->numrows();
		if ($matches == 0) {
			$spirits .="<red>There were no matches for $name with an Agility Requirement of $agility";
		}
		forEach ($data as $row) {
			$name = $row->name;
			$spot = $row->spot;
			$lvl = $row->level;
			$loid = $row->id;
			$agi = $row->agility;
			
			$data2 = $db->query("SELECT * FROM aodb WHERE lowid = $loid");
			forEach ($data2 as $row); {
				$hiid = $row->highid;
				$icon = $row->icon;
				$phat = $row->name;
				$hiql = $row->highql;
			}
			$spirits .= "<img src=rdb://".$icon."> ";
			$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
			$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility Needed=$agi<end>\n\n";
		}
	}
}
	//Search By Sense
else if (preg_match ("/^spiritssen ([0-9,]+)$/i", $message, $arr)) {
	$sense = $arr[1];
	$losense = $sense - 10;
	$title = "Search Spirits Database for Sense Requirement of $sense";
	$data = $db->query("SELECT * FROM spiritsdb WHERE sense < $sense AND sense > $losense ORDER BY level");
	$matches = $db->numrows();
	if ($matches == 0) {
		$spirits .="<red>There were no matches for Spirits with a Sense Requirement of $sense";
	}
	forEach ($data as $row) {
		$name = $row->name;
		$spot = $row->spot;
		$lvl = $row->level;
		$loid = $row->id;
		$sen = $row->sense;
		
		$data2 = $db->query("SELECT * FROM aodb WHERE lowid = '$loid'");
		forEach ($data2 as $row); {
			$hiid = $row->highid;
			$icon = $row->icon;
			$phat = $row->name;
			$hiql = $row->highql;
		}
		$spirits .= "<img src=rdb://".$icon."> ";
		$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
		$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Sense Needed=$sen<end>\n\n";
	}
}
	// If searched by Sensel and slot
else if (preg_match ("/^spiritssen ([0-9]+) (.+)$/i", $message, $arr)) {
	$sense = $arr[1];
	$losense = $sense - 10;
	$name = $arr[2];
	$name = ucwords(strtolower($name));
    if ($sense < 1 OR $sense > 1276) {
        $msg = "<red>No valid Sense specified(1-1276)";
        $chatBot->send($msg, $sendto);
        return;
    } else if (preg_match("/[^chest|ear|eye|feet|head|larm|legs|lhand|lwrist|rarm|rhand|rwrist|waist]/i",$name)) {
		$title = "Search Spirits Database Error";
		$spirits .= "<red>Invalid Input\n\n";
		$spirits .="If searching by Sense and Slot valid slot types are:\n";
		$spirits .="Head\n";
		$spirits .="Eye\n";
		$spirits .="Ear\n";
		$spirits .="Chest\n";
		$spirits .="Larm\n";
		$spirits .="Rarm\n";
		$spirits .="Waist\n";
		$spirits .="Lwrist\n";
		$spirits .="Rwrist\n";
		$spirits .="Legs\n";
		$spirits .="Lhand\n";
		$spirits .="Rhand\n";
		$spirits .="Feet\n";
	} else  {
		$title = "Search for $name Spirits With Sense Req of $sense";
		$data = $db->query("SELECT * FROM spiritsdb where spot = '".str_replace("'", "''", $name)."' AND sense < $sense AND sense > $losense ORDER BY ql");
		$matches = $db->numrows();
		if ($matches == 0) {
			$spirits .="<red>There were no matches for $name with a Sense Requirement of $sense";
		}
		forEach ($data as $row) {
			$name = $row->name;
			$spot = $row->spot;
			$lvl = $row->level;
			$loid = $row->id;
			$agi = $row->agility;
			
			$data2 = $db->query("SELECT * FROM aodb WHERE lowid = $loid");
			forEach ($data2 as $row); {
				$hiid = $row->highid;
				$icon = $row->icon;
				$phat = $row->name;
				$hiql = $row->highql;
			}
			$spirits .= "<img src=rdb://".$icon."> ";
			$spirits .= "<a href='itemref://$loid/$hiid/$hiql.'>$phat</a>\n";
			$spirits .= "<green>Minimum Level=$lvl   Slot=$spot   Agility Needed=$agi<end>\n\n";
		}
	}
} else {
	$syntax_error = true;
	return;
}
		
$spirits = Text::makeLink($title, $spirits);
		
$chatBot->send($spirits, $sendto);
?>