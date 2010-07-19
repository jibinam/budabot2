<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Items DB search
   ** Version: 0.8
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 23.11.2005
   ** Date(last modified): 22.11.2006
   ** 
   ** Copyright (C) 2005, 2006 Carsten Lohmann
   **
   ** Licence Infos: 
   ** This file is part of Budabot.
   **
   ** Budabot is free software; you can redistribute it and/or modify
   ** it under the terms of the GNU General Public License as published by
   ** the Free Software Foundation; either version 2 of the License, or
   ** (at your option) any later version.
   **
   ** Budabot is distributed in the hope that it will be useful,
   ** but WITHOUT ANY WARRANTY; without even the implied warranty of
   ** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   ** GNU General Public License for more details.
   **
   ** You should have received a copy of the GNU General Public License
   ** along with Budabot; if not, write to the Free Software
   ** Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
   */
   
if (preg_match("/^items ([0-9]+) (.+)$/i", $message, $arr)) {
    $ql = $arr[1];
    if (!($ql >= 1 && $ql <= 500)) {
        $msg = "Invalid Ql specified(1-500)";
        $this->send($msg, $sendto);
        return;
    }
    $name = $arr[2];
} else if (preg_match("/^items (.+)$/i", $message, $arr)) {
    $name = $arr[1];
    $ql = false;
} else {
  	$msg = "You need to specify an item to be searched for!";
	$this->send($msg, $sendto);
	return;  	
}

// ao automatically converts '&' to '&amp;', so we convert it back
$name = str_replace("&amp;", "&", $name);

$tmp = explode(" ", $name);
$first = true;
forEach ($tmp as $key => $value) {
	// escape single quotes to prevent sql injection
	$value = str_replace("'", "''", $value);
	if($first) {
		$query .= "`name` LIKE '%$value%'";
		$first = false;
	} else
		$query .= " AND `name` LIKE '%$value%'";		
}

if($ql) {
	$query .= " AND `lowql` <= $ql AND `highql` >= $ql";
}

$db->query("SELECT * FROM aodb WHERE $query ORDER BY `name` LIMIT 0, {Settings::get("maxitems")}");
$num = $db->numrows();
if ($num == 0) {
  	if ($ql) {
	    $msg = "No items found with QL <highlight>$ql<end>. Maybe try fewer keywords.";
	} else {
	    $msg = "No items found. Maybe try fewer keywords.";
	}
   	$this->send($msg, $sendto);
	return;
}

$countitems = 0;

while($row = $db->fObject()) {
	if(!isset($itemlist[$row->name])) {
		$itemlist[$row->name] = array(array("lowid" => $row->lowid, "highid" => $row->highid, "lowql" => $row->lowql, "highql" => $row->highql, "icon" => $row->icon));
		$countitems++;
	} elseif(isset($itemlist[$row->name])) {
	  	if($itemlist[$row->name][0]["lowql"] > $row->lowql) {
		    $itemlist[$row->name][0]["lowql"] = $row->lowql;
		    $itemlist[$row->name][0]["lowid"] = $row->lowid;
		} elseif($itemlist[$row->name][0]["highql"] < $row->highql) {
		    $itemlist[$row->name][0]["highql"] = $row->highql;
		    $itemlist[$row->name][0]["highid"] = $row->highid;		    
		} else {
			$tmp = $itemlist[$row->name];
			$tmp[] = array("lowid" => $row->lowid, "highid" => $row->highid, "lowql" => $row->lowql, "highql" => $row->highql, "icon" => $row->icon);
			$itemlist[$row->name] = $tmp;
			$countitems++;
		}
	}
}

if ($countitems == 0) {
  	if ($ql) {
	    $msg = "No items found with QL <highlight>$ql<end>. Maybe try fewer keywords.";
	} else {
	    $msg = "No items found. Maybe try fewer keywords.";
	}
   	$this->send($msg, $sendto);
	return;
}

if ($countitems > 3) {
	forEach ($itemlist as $name => $item1) {
	 	forEach ($item1 as $key => $item) {
	        $list .= "<img src=rdb://".$item["icon"]."> \n";
	        if ($ql) {
		        $list .= "QL $ql ".$this->makeItem($item["lowid"], $item["highid"], $ql, $name);
			} else {
		        $list .= $this->makeItem($item["lowid"], $item["highid"], $item["highql"], $name);		  
			}
	
	        if ($item["lowql"] != $item["highql"]) {
		        $list .= " (QL".$item["lowql"]." - ".$item["highql"].")\n\n";
	        } else {
	    	    $list .= " (QL".$item["lowql"].")\n\n";
			}
	    }
    }
    $list = "<header>::::: Item Search Result :::::<end>\n\n".$list;
    $link = $this->makeLink("$countitems results in total", $list);
    $this->send($link, $sendto);

	//Show a warning if the maxitems are reached
	if ($countitems == Settings::get("maxitems")) {
	    $msg = "The output has been limited to <highlight>{Settings::get("maxitems")}<end> items. Specify your search more if your item isn't listed.";
	    $this->send($msg, $sendto);
	}
} else {
    forEach ($itemlist as $name => $item1) {
   	 	forEach ($item1 as $key => $item) {
	        if ($ql) {
		        $link .= "\n QL $ql ".$this->makeItem($item["lowid"], $item["highid"], $ql, $name);
			} else {
		        $link .= "\n".$this->makeItem($item["lowid"], $item["highid"], $item["highql"], $name);
	        }
			
	        if ($item["lowql"] != $item["highql"]) {
		        $link .= " (QL".$item["lowql"]." - ".$item["highql"].")";
	        } else {
	            $link .= " (QL".$item["lowql"].")";
			}
	    }
    }

	// Send info back
    $this->send($link, $sendto);
}
?>