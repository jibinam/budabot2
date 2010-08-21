<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Handles Guestrelay Member Logon
   ** Version: 1.0
   **
   ** Developed for: Budabot(http://sourceforge.net/projects/budabot)
   **
   ** Date(created): 23.11.2005
   ** Date(last modified): 21.11.2006
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

if (Settings::get("relaybot") != "Off" && isset($chatBot->guildmembers[$sender])) {
    $msg = "";
    $row = $db->query("SELECT * FROM org_members_<myname> WHERE `name` = '$sender'", true);
	$numrows = $db->numrows();
	if ($row->mode != "del" && $numrows == 1) {
        if (time() >= $chatBot->vars["onlinedelay"]) {
            if ($row->firstname) {
                $msg = $row->firstname." ";
			}

            $msg .= "<highlight>\"".$row->name."\"<end> ";

            if ($row->lastname) {
                $msg .= $row->lastname." ";
			}

            $msg .= "(Level <highlight>$row->level<end>/<green>$row->ai_level - $row->ai_rank<end>, <highlight>$row->profession<end>,";

            if ($row->guild) {
                $msg .= " $row->rank of <highlight>$row->guild<end>) ";
            } else {
                $msg .= " Not in a guild.) ";
			}

            $msg .= "logged on. ";

            $logon_msg = $row->logon_msg;

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
                $list .= ":::::::: Main Character\n";
                $list .= "<tab><tab>".Text::makeLink($row->main, "/tell <myname> whois $main", "chatcmd")." - ";
				$online = $chatBot->buddy_online($main);
				if ($online === null) {
				   $list .= "No status.\n";
				} else if ($online == 1) {
				   $list .= "<green>Online<end>\n";
				} else {
				   $list .= "<red>Offline<end>\n";
				}

                $list .= ":::::::: Alt Character(s)\n";
                $data = $db->query("SELECT * FROM alts WHERE `main` = '$main'");
                forEach ($data as $row) {
                    $list .= "<tab><tab>".Text::makeLink($row->alt, "/tell <myname> whois $row->alt", "chatcmd")." - ";
					$online = $chatBot->buddy_online($row->alt);
                    if ($online === null) {
                       $list .= "No status.\n";
                    } else if ($online == 1) {
                       $list .= "<green>Online<end>\n";
                    } else {
                       $list .= "<red>Offline<end>\n";
					}
                }
            }

			if ($main != $sender && $main != false) {
				$alts = Text::makeLink("Alts of $main", $list);
				$msg .= "Main: <highlight>$main<end> ($alts) ";
			} elseif($main != false) {
	  			$alts = Text::makeLink("Alts of $main", $list);
				$msg .= "$alts ";  
			}
		
            if ($logon_msg != '0') {
                $msg .= " - ".$logon_msg;
			}

			send_message_to_relay("grc ".$msg);
        }
    }
}
?>
