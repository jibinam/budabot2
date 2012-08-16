<?
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

if(eregi("^(adv|agent|crat|doc|enf|eng|fix|keep|ma|mp|nt|sol|shade|trader)$", $message, $arr)) {
    switch(strtolower($arr[1])) {
        case "adv":
            $prof = "Adventurer";
            break;
        case "agent":
            $prof = "Agent";
            break;
        case "crat":
            $prof = "Bureaucrat";
            break;
        case "doc":
            $prof = "Doctor";
            break;
        case "enf":
            $prof = "Enforcer";
            break;
        case "eng":
            $prof = "Engineer";
            break;
        case "fix":
            $prof = "Fixer";
            break;
        case "keep":
            $prof = "Keeper";
            break;
        case "ma":
            $prof = "Martial Artist";
            break;
        case "mp":
            $prof = "Meta-Physicist";
            break;
        case "nt":
            $prof = "Nano-Technician";
            break;
        case "sol":
            $prof = "Soldier";
            break;
        case "trader":
            $prof = "Trader";
            break;
        case "shade":
            $prof = "Shade";
            break;
    }
    if($type == "guild" || ($this->settings["count_tell"] == 0 && $type == "msg") || ($type == "priv" && $this->vars["Guest"][$sender] == true)) {
	    if($this->settings["relaybot"])
			$db->query("SELECT name, profession, level, afk FROM guild_chatlist_<myname> WHERE `profession` = '$prof' UNION ALL SELECT name, profession, level, afk FROM guild_chatlist_".strtolower($this->settings["relaybot"])." WHERE `profession` = '$prof' UNION ALL SELECT name, profession, level, afk FROM priv_chatlist_<myname> WHERE `profession` = '$prof' AND `guest` = 1 ORDER BY level");
	    else
		    $db->query("SELECT name, profession, level, afk FROM guild_chatlist_<myname> WHERE `profession` = '$prof' UNION ALL SELECT name, profession, level, afk FROM priv_chatlist_<myname> WHERE `profession` = '$prof' AND `guest` = 1 ORDER BY level"); 
	} elseif($type == "priv" || ($this->settings["count_tell"] == 1 && $type == "msg")) {
	  	$db->query("SELECT * FROM priv_chatlist_<myname> WHERE `profession` = '$prof' ORDER BY `level`");
	}
    $numonline = $db->numrows();
    $msg = "<highlight>$numonline<end> $prof:";

    while($row = $db->fObject()) {
        if($row->afk == "kiting")
            $afk = "<red>*KITING*<end>";
		elseif($row->afk != "0")
            $afk = "<red>*AFK*<end>";
        else
            $afk = "";
        $msg .= " [<highlight>$row->name<end> - ".$row->level.$afk."]";
    }
    if($type == "msg")
        bot::send($msg, $sender);
    elseif($type == "priv")
       	bot::send($msg);
    elseif($type == "guild")
       	bot::send($msg, "guild");  	
} elseif(eregi("^count (level|lvl)$", $message, $arr)) {
	$tl1 = 0;
	$tl2 = 0;
	$tl3 = 0;
	$tl4 = 0;
	$tl5 = 0;
	$tl6 = 0;
	$tl7 = 0;
	if($type == "guild" || ($this->settings["count_tell"] == 0 && $type == "msg") || ($type == "priv" && $this->vars["Guest"][$sender] == true)) {							
	    if($this->settings["relaybot"])
		    $db->query("SELECT name, profession, level, afk FROM guild_chatlist_<myname> UNION ALL SELECT name, profession, level, afk FROM guild_chatlist_".strtolower($this->settings["relaybot"])." UNION ALL SELECT name, profession, level, afk FROM priv_chatlist_<myname> WHERE `guest` = 1 ORDER BY level");
	    else
		    $db->query("SELECT name, profession, level, afk FROM guild_chatlist_<myname> UNION ALL SELECT name, profession, level, afk FROM priv_chatlist_<myname> WHERE `guest` = 1 ORDER BY level"); 
 	} elseif($type == "priv"  || ($this->settings["count_tell"] == 1 && $type == "msg")) {
	  	$db->query("SELECT * FROM priv_chatlist_<myname>");
	} 
	$numonline = $db->numrows();
    while($row = $db->fObject()) {
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
    if($type == "msg")
        bot::send($msg, $sender);
    elseif($type == "priv")
       	bot::send($msg);
    elseif($type == "guild")
       	bot::send($msg, "guild"); 
} else if(eregi("^count (.*)$", $message, $arr)) {
    switch(strtolower($arr[1])) {
        case "all":
            $prof = "all";
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
            break;
        case "adv":
            $prof = "Adventurer";
            $online[$prof] = 0;
            break;
        case "agent":
            $prof = "Agent";
            $online[$prof] = 0;
            break;
        case "crat":
            $prof = "Bureaucrat";
            $online[$prof] = 0;
            break;
        case "doc":
            $prof = "Doctor";
            $online[$prof] = 0;
            break;
        case "enf":
            $prof = "Enforcer";
            $online[$prof] = 0;
            break;
        case "eng":
            $prof = "Engineer";
            $online[$prof] = 0;
            break;
        case "fix":
            $prof = "Fixer";
            $online[$prof] = 0;
            break;
        case "keep":
            $prof = "Keeper";
            $online[$prof] = 0;
            break;
        case "ma":
            $prof = "Martial Artist";
            $online[$prof] = 0;
            break;
        case "mp":
            $prof = "Meta-Physicist";
            $online[$prof] = 0;
            break;
        case "nt":
            $prof = "Nano-Technician";
            $online[$prof] = 0;
            break;
        case "sol":
            $prof = "Soldier";
            $online[$prof] = 0;
            break;
        case "trader":
            $prof = "Trader";
            $online[$prof] = 0;
            break;
        case "shade":
            $prof = "Shade";
            $online[$prof] = 0;
            break;
    }
    if(!$prof) {
        $msg = "Please choose one of these professions: adv, agent, crat, doc, enf, eng, fix, keep, ma, mp, nt, sol, shade, trader or all";
	    if($type == "msg")
	        bot::send($msg, $sender);
	    elseif($type == "priv")
	       	bot::send($msg);
	    elseif($type == "guild")
	       	bot::send($msg, "guild");
	    return;
	}
	if($type == "guild" || ($this->settings["count_tell"] == 0 && $type == "msg") || ($type == "priv" && $this->vars["Guest"][$sender] == true)) {
	    if($this->settings["relaybot"]) {
	        if($prof == "all") {
				$db->query("SELECT name, profession, level, afk FROM guild_chatlist_<myname> UNION ALL SELECT name, profession, level, afk FROM guild_chatlist_".strtolower($this->settings["relaybot"])." UNION ALL SELECT name, profession, level, afk FROM priv_chatlist_<myname> WHERE `guest` = 1 ORDER BY profession");
	            $numonline = $db->numrows();
	            $msg = "<highlight>$numonline<end> in total: ";
	        } else {
				$db->query("SELECT name, profession, level, afk FROM guild_chatlist_<myname> WHERE `profession` = '$prof' UNION ALL SELECT name, profession, level, afk FROM guild_chatlist_".strtolower($this->settings["relaybot"])." WHERE `profession` = '$prof' UNION ALL SELECT name, profession, level, afk FROM priv_chatlist_<myname> WHERE `profession` = '$prof' AND `guest` = 1 ORDER BY level");
	            $numonline = $db->numrows();
	            $msg = "<highlight>$numonline<end> $prof:";
	        }
	    } else {
	        if($prof == "all") {
				$db->query("SELECT name, profession, level, afk FROM guild_chatlist_<myname> UNION ALL SELECT name, profession, level, afk FROM priv_chatlist_<myname> WHERE `guest` = 1 ORDER BY profession");
	            $numonline = $db->numrows();
	            $msg = "<highlight>$numonline<end> in total: ";
	        } else {
				$db->query("SELECT name, profession, level, afk FROM guild_chatlist_<myname> WHERE `profession` = '$prof' UNION ALL SELECT name, profession, level, afk FROM priv_chatlist_<myname> WHERE `profession` = '$prof' AND `guest`= 1 ORDER BY level");
	            $numonline = $db->numrows();
	            $msg = "<highlight>$numonline<end> $prof:";
	        }
		}
 	} elseif($type == "priv" || ($this->settings["count_tell"] == 1 && $type == "msg")) {
        if($prof == "all") {
            $db->query("SELECT * FROM priv_chatlist_<myname> ORDER BY `profession`");
            $numonline = $db->numrows();
            $msg = "<highlight>$numonline<end> in total: ";
        } else {
            $db->query("SELECT * FROM priv_chatlist_<myname> WHERE `profession` = '$prof' ORDER BY `level`");
            $numonline = $db->numrows();
            $msg = "<highlight>$numonline<end> $prof:";
        }
	}  

    while($row = $db->fObject()) {
	    if($prof == "all")
    	    $online[$row->profession]++;
        else {
            if($row->afk == "kiting")
            	$afk = "<red>*KITING*<end>";
			elseif($row->afk != "0")
	            $afk = "<red>*AFK*<end>";
            else
                $afk = "";
    	    $msg .= " [<highlight>$row->name<end> - ".$row->level.$afk."]";
        }
	}

    if($prof == "all") {
	    $msg .= "<highlight>".$online['Adventurer']."<end> Adv, <highlight>".$online['Agent']."<end> Agent, <highlight>".$online['Bureaucrat']."<end> Crat, <highlight>".$online['Doctor']."<end> Doc, <highlight>".$online['Enforcer']."<end> Enf, <highlight>".$online['Engineer']."<end> Eng, <highlight>".$online['Fixer']."<end> Fix, <highlight>".$online['Keeper']."<end> Keeper, <highlight>".$online['Martial Artist']."<end> MA, <highlight>".$online['Meta-Physicist']."<end> MP, <highlight>".$online['Nano-Technician']."<end> NT, <highlight>".$online['Soldier']."<end> Sol, <highlight>".$online['Shade']."<end> Shade, <highlight>".$online['Trader']."<end> Trader";
    }

  	if($type == "msg")
        bot::send($msg, $sender);
    elseif($type == "priv")
       	bot::send($msg);
    elseif($type == "guild")
       	bot::send($msg, "guild");
}
?>