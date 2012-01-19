<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Shows logon from Guildmembers
   ** Version: 1.1
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 23.11.2005
   ** Date(last modified): 26.11.2006
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

$msg = "";
$org_member = $db->query("SELECT * FROM org_members_<myname> WHERE `name` = '$sender'", true);
$numrows = $db->numrows();
if($org_member->mode != "del" && $numrows == 1) {
  	$db->query("SELECT * FROM guild_chatlist_<myname> WHERE `name` = '$sender'");
	if ($db->numrows() != 0) {
	    $db->execute("UPDATE guild_chatlist_<myname> SET `profession` = '$org_member->profession', `guild` = '$org_member->guild', `rank` = '$org_member->rank', `breed` = '$org_member->breed', `level` = '$org_member->level', `ai_level` = '$org_member->ai_level' WHERE `name` = '$sender'");
	} else {
	    $db->execute("INSERT INTO guild_chatlist_<myname> (`name`, `profession`, `guild`, `rank`, `breed`, `level`, `ai_level`) VALUES ('$org_member->name', '$org_member->profession', '$org_member->guild', '$org_member->rank', '$org_member->breed', '$org_member->level', '$org_member->ai_level')");
	}

    if (time() >= $chatBot->vars["onlinedelay"]) {
        if ($org_member->firstname) {
            $msg = $org_member->firstname." ";
		}

        $msg .= "<highlight>\"".$org_member->name."\"<end> ";

        if ($org_member->lastname) {
            $msg .= $org_member->lastname." ";
		}

        $msg .= "(Level <highlight>$org_member->level<end>/<green>$org_member->ai_level - $org_member->ai_rank<end>, $org_member->gender $org_member->breed <highlight>$org_member->profession<end>,";

        if ($org_member->guild) {
            $msg .= " $org_member->rank of <highlight>$org_member->guild<end>) ";
        } else {
            $msg .= " Not in a guild.) ";
		}

        $msg .= "logged on. ";

        // Alternative Characters Part
        $main = false;
        // Check if $sender is hisself the main
        $db->query("SELECT * FROM alts WHERE `main` = '$sender'");
        if ($db->numrows() == 0) {
            // Check if $sender is an alt
            $row = $db->query("SELECT * FROM alts WHERE `alt` = '$sender'", true);
            if ($db->numrows() != 0) {
                $main = $row->main;
            }
        } else {
            $main = $sender;
		}

		// If a main was found create the list
        if ($main) {
            $list = "<header>::::: Alternative Character List :::::<end> \n \n";
            $list .= ":::::: Main Character\n";
            $list .= "<tab><tab>".Text::make_link($main, "/tell <myname> whois $main", "chatcmd")." - ";
			$online = Buddylist::is_online($main);
            if ($online === null) {
                $list .= "No status.\n";
            } else if ($online == 1) {
                $list .= "<green>Online<end>\n";
            } else { // if ($online == 0)
                $list .= "<red>Offline<end>\n";
			}

            $list .= ":::::: Alt Character(s)\n";
            $data = $db->query("SELECT * FROM alts WHERE `main` = '$main'");
            forEach ($data as $row) {
                $list .= "<tab><tab>".Text::make_link($row->alt, "/tell <myname> whois $row->alt", "chatcmd")." - ";
				$online = Buddylist::is_online($row->alt);
                if ($online === null) {
                    $list .= "No status.\n";
                } else if ($online == 1) {
                    $list .= "<green>Online<end>\n";
                } else { // if ($online == 0)
                    $list .= "<red>Offline<end>\n";
				}
            }
        }

		if ($main != $sender && $main != false) {
			$alts = Text::make_link("Alts", $list);
			$msg .= "Main: <highlight>$main<end> ($alts) ";
		} else if ($main != false) {
  			$alts = Text::make_link("Alts of $main", $list);
			$msg .= "$alts ";
		}

        if ($org_member->logon_msg != '0') {
            $msg .= " - " . $org_member->logon_msg;
		}

       	$chatBot->send($msg, "guild", true);

		//Guestchannel part
		if (Settings::get("guest_relay") == 1) {
			$chatBot->send($msg, "priv", true);
		}
    }
}
?>