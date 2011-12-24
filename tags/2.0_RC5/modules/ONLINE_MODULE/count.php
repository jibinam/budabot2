<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Who is online(count design)
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 23.11.2005
   ** Date(last modified): 23.01.2007
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

if (preg_match("/^(adv|agent|crat|doc|enf|eng|fix|keep|ma|mp|nt|sol|shade|trader)$/i", $message, $arr)) {
    $prof = Util::get_profession_name($arr[1]);
	if ($prof == '') {
        $msg = "Please choose one of these professions: adv, agent, crat, doc, enf, eng, fix, keep, ma, mp, nt, sol, shade, trader or all";
	    $chatBot->send($msg, $sendto);
	    return;
	}
    if ($type == "guild" || ($chatBot->settings["count_tell"] == 0 && $type == "msg") || $type == "priv") {
		$sql = "
			SELECT * FROM guild_chatlist_<myname> g LEFT JOIN players p2 ON g.name = p2.name WHERE p2.`profession` = '$prof'
			UNION ALL
			SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name WHERE p2.`profession` = '$prof'
			ORDER BY level";
		$db->query($sql); 
	} else if ($type == "priv" || ($chatBot->settings["count_tell"] == 1 && $type == "msg")) {
		$sql = "SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name WHERE `profession` = '$prof' ORDER BY `level`";
	  	$db->query($sql);
	}
    $numonline = $db->numrows();
    $msg = "<highlight>$numonline<end> $prof:";

    while ($row = $db->fObject()) {
        if($row->afk == "kiting")
            $afk = "<red>*KITING*<end>";
		elseif($row->afk != "0")
            $afk = "<red>*AFK*<end>";
        else
            $afk = "";
        $msg .= " [<highlight>$row->name<end> - ".$row->level.$afk."]";
    }
    $chatBot->send($msg, $sendto);  	
} else if (preg_match("/^count (level|lvl)$/i", $message, $arr)) {
	$tl1 = 0;
	$tl2 = 0;
	$tl3 = 0;
	$tl4 = 0;
	$tl5 = 0;
	$tl6 = 0;
	$tl7 = 0;
	if ($type == "guild" || ($chatBot->settings["count_tell"] == 0 && $type == "msg") || $type == "priv") {
		$sql = "
			SELECT * FROM guild_chatlist_<myname> g LEFT JOIN players p ON g.name = p.name
			UNION ALL
			SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name ORDER BY level";
		$db->query($sql);
 	} else if ($type == "priv"  || ($chatBot->settings["count_tell"] == 1 && $type == "msg")) {
	  	$db->query("SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name");
	} 
	$numonline = $db->numrows();
    while ($row = $db->fObject()) {
      	if($row->level > 1 && $row->level <= 14)
      		$tl1++;
      	elseif($row->level >= 15 && $row->level <= 49)
      		$tl2++;
      	elseif($row->level >= 50 && $row->level <= 99)
      		$tl3++;
      	elseif($row->level >= 100 && $row->level <= 149)
      		$tl4++;
      	elseif($row->level >= 150 && $row->level <= 189)
      		$tl5++;
      	elseif($row->level >= 190 && $row->level <= 204)
      		$tl6++;
      	elseif($row->level >= 205 && $row->level <= 220)
      		$tl7++;
    }	
    $msg = "<highlight>$numonline<end> in total: TL1 <highlight>$tl1<end>, TL2 <highlight>$tl2<end>, TL3 <highlight>$tl3<end>, TL4 <highlight>$tl4<end>, TL5 <highlight>$tl5<end>, TL6 <highlight>$tl6<end>, TL7 <highlight>$tl7<end>";
    $chatBot->send($msg, $sendto);
} else if (preg_match("/^count (.*)$/i", $message, $arr)) {
	if ($arr[1] == 'all') {
		$prof = 'all';
		$online["Adventurer"] = 0;
		$online["Agent"] = 0;
		$online["Bureaucrat"] = 0;
		$online["Doctor"] = 0;
		$online["Enforcer"] = 0;
		$online["Engineer"] = 0;
		$online["Fixer"] = 0;
		$online["Keeper"] = 0;
		$online["Martial Artist"] = 0;
		$online["Meta-Physicist"] = 0;
		$online["Martial Artist"] = 0;
		$online["Nano-Technician"] = 0;
		$online["Soldier"] = 0;
		$online["Trader"] = 0;
		$online["Shade"] = 0;
	} else {
		$prof = Util::get_profession_name($arr[1]);
		if ($prof == '') {
			$msg = "Please choose one of these professions: adv, agent, crat, doc, enf, eng, fix, keep, ma, mp, nt, sol, shade, trader or all";
			$chatBot->send($msg, $sendto);
			return;
		} else {
			$online[$prof] = 0;
		}
	}

	if ($type == "guild" || ($chatBot->settings["count_tell"] == 0 && $type == "msg") || $type == "priv") {
	    if ($prof == "all") {
			$sql = "
				SELECT * FROM guild_chatlist_<myname> g LEFT JOIN players p ON g.name = p.name
				UNION ALL
				SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name ORDER BY profession";
			$db->query($sql);
			$numonline = $db->numrows();
			$msg = "<highlight>$numonline<end> in total: ";
		} else {
			$sql = "
				SELECT * FROM guild_chatlist_<myname> g LEFT JOIN players p ON g.name = p.name WHERE `profession` = '$prof'
				UNION ALL
				SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name WHERE `profession` = '$prof' ORDER BY level";
			$db->query($sql);
			$numonline = $db->numrows();
			$msg = "<highlight>$numonline<end> $prof:";
		}
 	} else if ($type == "priv" || ($chatBot->settings["count_tell"] == 1 && $type == "msg")) {
        if ($prof == "all") {
            $db->query("SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name ORDER BY `profession`");
            $numonline = $db->numrows();
            $msg = "<highlight>$numonline<end> in total: ";
        } else {
            $db->query("SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name WHERE `profession` = '$prof' ORDER BY `level`");
            $numonline = $db->numrows();
            $msg = "<highlight>$numonline<end> $prof:";
        }
	}  

    while ($row = $db->fObject()) {
	    if ($prof == "all") {
    	    $online[$row->profession]++;
        } else {
            if($row->afk == "kiting")
            	$afk = "<red>*KITING*<end>";
			elseif($row->afk != "0")
	            $afk = "<red>*AFK*<end>";
            else
                $afk = "";
    	    $msg .= " [<highlight>$row->name<end> - ".$row->level.$afk."]";
        }
	}

    if ($prof == "all") {
	    $msg .= "<highlight>".$online['Adventurer']."<end> Adv, <highlight>".$online['Agent']."<end> Agent, <highlight>".$online['Bureaucrat']."<end> Crat, <highlight>".$online['Doctor']."<end> Doc, <highlight>".$online['Enforcer']."<end> Enf, <highlight>".$online['Engineer']."<end> Eng, <highlight>".$online['Fixer']."<end> Fix, <highlight>".$online['Keeper']."<end> Keeper, <highlight>".$online['Martial Artist']."<end> MA, <highlight>".$online['Meta-Physicist']."<end> MP, <highlight>".$online['Nano-Technician']."<end> NT, <highlight>".$online['Soldier']."<end> Sol, <highlight>".$online['Shade']."<end> Shade, <highlight>".$online['Trader']."<end> Trader";
    }

  	$chatBot->send($msg, $sendto);
} else {
	$syntax_error = true;
}

?>