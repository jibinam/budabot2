<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Shows infos about players
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 10.12.2005
   ** Date(last modified): 10.12.2005
   ** 
   ** Copyright (C) 2005 Carsten Lohmann
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

$msg = "";
if (preg_match("/^whois (.+)$/i", $message, $arr)) {
    $uid = $chatBot->get_uid($arr[1]);
    $name = ucfirst(strtolower($arr[1]));
    if ($uid) {
        $whois = Player::get_by_name($arr[1]);
        if ($whois === null) {
        	$msg = "Could not find character info for {$name}.";
        } else {
	        $msg = Player::get_info($whois);

	        $list = "<header>::::: Detailed info for {$name} :::::<end>\n\n";
	        $list .= "Name: <highlight>{$whois->firstname} \"{$name}\" {$whois->lastname}<end>\n";
			if ($whois->guild) {
				$list .= "Guild: <highlight>{$whois->guild} ({$whois->guild_id})<end>\n";
				$list .= "Guild Rank: <highlight>{$whois->guild_rank} ({$whois->guild_rank_id})<end>\n";
			}
			$list .= "Breed: <highlight>{$whois->breed}<end>\n";
			$list .= "Gender: <highlight>{$whois->gender}<end>\n";
			$list .= "Profession: <highlight>{$whois->profession} ({$whois->prof_title})<end>\n";
			$list .= "Level: <highlight>{$whois->level}<end>\n";
			$list .= "AI Level: <highlight>{$whois->ai_level} ({$whois->ai_rank})<end>\n";
			$list .= "Faction: <highlight>{$whois->faction}<end>\n";
			$list .= "Character ID: <highlight>{$whois->charid}<end>\n\n";
			
			$list .= "Source: $whois->source\n\n";
			
	        $list .= "<a href='chatcmd:///tell <myname> history $name'>Check $name's History</a>\n";
	        $list .= "<a href='chatcmd:///tell <myname> is $name'>Check $name's online status</a>\n";
	        if ($whois->guild) {
		        $list .= "<a href='chatcmd:///tell <myname> whoisorg $whois->guild_id'>Show info about {$whois->guild}</a>\n";
				$list .= "<a href='chatcmd:///tell <myname> orglist $whois->guild_id'>Orglist for {$whois->guild}</a>\n";
			}
	        $list .= "<a href='chatcmd:///cc addbuddy $name'>Add to buddylist</a>\n";
	        $list .= "<a href='chatcmd:///cc rembuddy $name'>Remove from buddylist</a>";
			
	        $msg .= " :: ".Text::make_link("More info", $list, 'blob');
	    }
    } else {
        $msg = "Player <highlight>$name<end> does not exist.";
	}

    // Send info back
    $chatBot->send($msg, $sendto);
} else if (preg_match("/^whoisall (.+)$/i", $message, $arr)) {
    $name = ucfirst(strtolower($arr[1]));
    for ($i = 1; $i <= 3; $i ++) {
        if ($i == 1) {
            $server = "Atlantean";
        } else if ($i == 2) {
            $server = "Rimor";
        } else {
            $server = "Die Neue Welt";
		}
        $msg = "";
        $whois = Player::lookup($name, $i);
        if ($whois !== null) {
            $msg = Player::get_info($whois);

           $list = "<header>::::: Detailed info for {$name} :::::<end>\n\n";
	        $list .= "Name: <highlight>{$whois->firstname} \"{$name}\" {$whois->lastname}<end>\n";
			if ($whois->guild) {
				$list .= "Guild: <highlight>{$whois->guild} ({$whois->guild_id})<end>\n";
				$list .= "Guild Rank: <highlight>{$whois->guild_rank} ({$whois->guild_rank_id})<end>\n";
			}
			$list .= "Breed: <highlight>{$whois->breed}<end>\n";
			$list .= "Gender: <highlight>{$whois->gender}<end>\n";
			$list .= "Profession: <highlight>{$whois->profession} ({$whois->prof_title})<end>\n";
			$list .= "Level: <highlight>{$whois->level}<end>\n";
			$list .= "AI Level: <highlight>{$whois->ai_level} ({$whois->ai_rank})<end>\n";
			$list .= "Faction: <highlight>{$whois->faction}<end>\n";
			$list .= "Character ID: <highlight>{$whois->charid}<end>\n\n";
			
			$list .= "Source: $whois->source\n\n";

            $list .= "<a href='chatcmd:///tell <myname> history $name'>Check $name's History</a>\n";
            $list .= "<a href='chatcmd:///tell <myname> is $name'>Check $name's online status</a>\n";
            $list .= "<a href='chatcmd:///cc addbuddy $name'>Add to buddylist</a>\n";
            $list .= "<a href='chatcmd:///cc rembuddy $name'>Remove from buddylist</a>";
			
            $msg .= " :: ".Text::make_link("More info", $list, 'blob');
            $msg = "<highlight>Server $server:<end> ".$msg;
        } else {
            $msg = "Server $server: Player <highlight>$name<end> does not exist.";
		}
        // Send info back
        $chatBot->send($msg, $sendto);
    }
} else if (preg_match("/^whoisorg ([0-9]+)$/i", $message, $arr)) {
	$org_id = $arr[1];

  	$msg = "Getting Org info. Please standby.";
    $chatBot->send($msg, $sendto);
	
    $org = Guild::get_by_id($org_id);
	if ($org !== null) {
  		$num_adv = 0;
  		$num_agent = 0;
  		$num_crat = 0;
  		$num_crat = 0;
  		$num_enf = 0;
  		$num_eng = 0;
  		$num_fix = 0;
  		$num_keep = 0;
  		$num_ma = 0;
  		$num_mp = 0;
  		$num_nt = 0;
  		$num_shade = 0;
  		$num_sol = 0;
  		$num_trad = 0;
  		$lvl_min = 220;
  		$lvl_max = 1;

	  	$num_members = count($org->members);
	  	forEach ($org->members as $member) {
	  	  	if ($member->guild_rank_id == 0) {
				$president_name = $member->name;
				$president_prof = $member->profession;
				$president_lvl = $member->level;
				$president_gender = $member->gender;
				$president_breed = $member->breed;
				$faction = $member->faction;
			}
			$lvl_tot += $member->level;
			
			if ($lvl_min > $member->level) {
				$lvl_min = $member->level;
			}

			if ($lvl_max < $member->level) {
				$lvl_max = $member->level;
			}

			switch ($member->profession) {
			  	case "Adventurer":
			  		$num_adv++;
					break;
			  	case "Agent":
			  		$num_agent++;
					break;
			  	case "Bureaucrat":
			  		$num_crat++;
					break;
			  	case "Doctor":
			  		$num_doc++;
					break;
			  	case "Enforcer":
			  		$num_enf++;
					break;
			  	case "Engineer":
			  		$num_eng++;
					break;
			  	case "Fixer":
			  		$num_fix++;
					break;
			  	case "Keeper":
			  		$num_keep++;
					break;
			  	case "Martial Artist":
			  		$num_ma++;
					break;
			  	case "Meta-Physicist":
			  		$num_mp++;
					break;
			  	case "Nano-Technician":
			  		$num_nt++;
					break;
			  	case "Shade":
			  		$num_shade++;
					break;
			  	case "Soldier":
			  		$num_sol++;
					break;
			  	case "Trader":
			  		$num_trad++;
					break;
			}
		}
		$lvl_avg = round($lvl_tot/$num_members);
	  	$link  = "<header>::::: Organization Info ($org->orgname) :::::<end>\n\n";
		$link .= "<u>General Infos</u>\n";
		$link .= "<highlight>Faction:<end> $faction\n";
		$link .= "<highlight>Lowest lvl:<end> $lvl_min\n";
		$link .= "<highlight>Highest lvl:<end> $lvl_max\n";
		$link .= "<highlight>Average lvl:<end> $lvl_avg\n\n";

		$link .= "<u>President</u>\n";
	  	$link .= "<highlight>Name:<end> $president_name\n";
	  	$link .= "<highlight>Profession:<end> $president_prof\n";
	  	$link .= "<highlight>Level:<end> $president_lvl\n";
	  	$link .= "<highlight>Gender:<end> $president_gender\n";
	  	$link .= "<highlight>Breed:<end> $president_breed\n\n";
		  	
		$link .= "<u>Members</u>\n";
	  	$link .= "<highlight>Number of Members:<end> $num_members\n";
	  	$link .= "<highlight>Adventurer:<end> $num_adv (".round(($num_adv*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Agents:<end> $num_agent (".round(($num_agent*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Bureaucrats:<end> $num_crat (".round(($num_crat*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Doctors:<end> $num_doc (".round(($num_doc*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Enforcers:<end> $num_enf (".round(($num_enf*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Engineers:<end> $num_eng (".round(($num_eng*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Fixers:<end> $num_fix (".round(($num_fix*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Keepers:<end> $num_keep (".round(($num_keep*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Martial Artists:<end> $num_ma (".round(($num_ma*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Meta-Physicists:<end> $num_mp (".round(($num_mp*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Nano-Technicians:<end> $num_nt (".round(($num_nt*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Shades:<end> $num_shade (".round(($num_shade*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Soldiers:<end> $num_sol (".round(($num_sol*100)/$num_members, 1)."% of total)\n";
	  	$link .= "<highlight>Traders:<end> $num_trad (".round(($num_trad*100)/$num_members, 1)."% of total)\n";		  			  			  	
	  	$msg = Text::make_link("Org Info $org->orgname", $link, 'blob');
	} else {
		$msg = "Error in getting the Org info. Either that org doesn't exist or the AO server was too slow to respond.";
	}

    $chatBot->send($msg, $sendto);
}
?>