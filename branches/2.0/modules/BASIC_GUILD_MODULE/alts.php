<?php
   /*
   ** Author: Derroylo (RK2)
   ** Description: Alt Char Handling
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

if (preg_match("/^alts add (.+)$/i", $message, $arr)) {
    $name = ucfirst(strtolower($arr[1]));
    $uid = $chatBot->get_uid($arr[1]);
    if (!$uid) {
        $msg = "Player <highlight>$name<end> does not exist.";
    } else {
        $row = $db->query("SELECT * FROM alts WHERE `alt` = '$name'", true);
        if ($row->alt == $name) {
            $msg = "Player <highlight>$name<end> is already registered as alt from <highlight>$row->main<end>.";
        } else {
            $db->query("SELECT * FROM alts WHERE `main` = '$name'");
            if ($db->numrows() != 0) {
                $msg = "Player <highlight>$name<end> is already registered as main from someone.";
            } else {
				//check added to make sure the $sender himself isn't already an alt
				$row = $db->query("SELECT * FROM alts WHERE `alt` = '$sender'", true);
				if ($row->alt == $sender) {
					$db->query("INSERT INTO alts (`alt`, `main`) VALUES ('$name', '$row->main')");
					$msg = "<highlight>You<end> are already an alt of <highlight>$row->main<end>. <highlight>$name<end> has been registered as an alt of <highlight>$row->main<end>.";
				} else {
					$db->query("INSERT INTO alts (`alt`, `main`) VALUES ('$name', '$sender')");
					$msg = "<highlight>$name<end> has been registered as your alt.";
				}
            }
        }
    }
} else if (preg_match("/^alts (rem|del|remove|delete) (.+)$/i", $message, $arr)) {
    $name = ucfirst(strtolower($arr[2]));
    $uid = $chatBot->get_uid($arr[2]);
    if (!$uid) {
        $msg = "Player <highlight>".$name."<end> does not exist.";
    } else {
        $row = $db->query("SELECT * FROM alts WHERE `main` = '$sender' AND `alt` = '$name'", true);
        if ($row->main == $sender) {
            $db->query("DELETE FROM alts WHERE `main` = '$sender' AND `alt` = '$name'");
            $msg = "<highlight>$name<end> has been deleted from your alt list.";
		} else {
			//sender was not found as a main.  checking if he himself is an alt and let him be able to modify his own alts list
			$row = $db->query("SELECT * FROM alts WHERE `alt` = '$sender'", true);
			//retrieve his main, use the main's name to do searches and modifications with
			$main = $row->main;
			$db->query("SELECT * FROM alts WHERE main = '$main' AND alt = '$name'");
			if ($db->numrows() != 0) {
				$db->query("DELETE FROM alts WHERE main = '$main' AND alt = '$name'");
				$msg = "<highlight>$name<end> has been deleted from your ($main) alt list.";
			} else {
				$msg = "<highlight>$name<end> is not registered as your alt.";
			}
        }
    }
} else if (preg_match("/^alts (.+)$/i", $message, $arr)) {
    $name = ucfirst(strtolower($arr[1]));
    $uid = $chatBot->get_uid($arr[1]);
    if (!$uid) {
        $msg = "Player <highlight>".$name."<end> does not exist.";
    } else {
        $main = false;
        // Check if sender is himelf the main
        $db->query("SELECT * FROM alts WHERE `main` = '$name'");
        if ($db->numrows() == 0){
            // Check if sender is an alt
            $row = $db->query("SELECT * FROM alts WHERE `alt` = '$name'", true);
            if ($db->numrows() == 0) {
                $msg = "No alts are registered for <highlight>$name<end>.";
            } else {
                $main = $row->main;
            }
        } else {
            $main = $name;
		}

        // If a main was found create the list
        if ($main) {
            $list .= ":::::: Main Character\n";
            $list .= "<tab><tab>".Text::make_link($main, "/tell <myname> whois $main", "chatcmd")." - ";
			$online = Buddylist::is_online($main);
            if ($online === null) {
                $list .= "No status.\n";
            } else if ($online == 1) {
                $list .= "<green>Online<end>\n";
            } else {
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
                } else {
                    $list .= "<red>Offline<end>\n";
				}
            }
            $msg = Text::make_blob($main."'s Alts", $list);
        }
    }
} else if (preg_match("/^alts$/i", $message)) {
    $main = false;
    // Check if $sender is himself the main
    $db->query("SELECT * FROM alts WHERE `main` = '$sender'");
    if ($db->numrows() == 0){
        // Check if $sender is an alt
        $row = $db->query("SELECT * FROM alts WHERE `alt` = '$sender'", true);
        if ($db->numrows() == 0) {
            $msg = "No alts are registered for <highlight>$sender<end>.";
        } else {
            $main = $row->main;
        }
    } else {
        $main = $sender;
	}


    // If a main was found create the list
    if ($main) {
        $list .= ":::::: Main Character\n";
        $list .= "<tab><tab>".Text::make_link($main, "/tell <myname> whois $main", "chatcmd")." - ";
		$online = Buddylist::is_online($main);
        if ($online === null) {
            $list .= "No status.\n";
        } else if ($online == 1) {
            $list .= "<green>Online<end>\n";
        } else {
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
            } else {
                $list .= "<red>Offline<end>\n";
			}
        }
        $msg = Text::make_blob($sender."'s Alts", $list);
    }
} else if (preg_match("/^altsadmin (.+)$/i", $message, $arr)) {
	if (preg_match("/^add (.+) (.+)$/i", $arr[1], $names)) {
		if ($names[1] != '' && $names[2] != '') {
			$name_alt = ucfirst(strtolower($names[1]));
			$name_main = ucfirst(strtolower($names[2]));
			$uid1 = $chatBot->get_uid($names[1]);
			$uid2 = $chatBot->get_uid($names[2]);
			if (!$uid1) {
				$msg = "Player <highlight>$name_alt<end> does not exist.";
			}
			if (!$uid2) {
				$msg .= " Player <highlight>$name_main<end> does not exist.";
			}
			if ($uid1 && $uid2) {
				$row = $db->query("SELECT * FROM alts WHERE `alt` = '$name_alt'", true);
				if ($row->alt == $name_alt) {
					$msg = "Player <highlight>$name_alt<end> is already registered as alt from <highlight>$row->main<end>.";
				} else {
					$db->query("SELECT * FROM alts WHERE `main` = '$name_alt'");
					if ($db->numrows() != 0) {
						$msg = "Player <highlight>$name_alt<end> is already registered as main from someone.";
					} else {
						$db->query("INSERT INTO alts (`alt`, `main`) VALUES ('$name_alt', '$name_main')");
						$msg = "<highlight>$name_alt<end> has been registered as an alt of $name_main.";
					}
				}
			}
		}
	} else if (preg_match("/^rem (.+) (.+)$/i", $arr[1], $names)) {
		if ($names[1] != '' && $names[2] != '') {
			$name_alt = ucfirst(strtolower($names[1]));
			$name_main = ucfirst(strtolower($names[2]));
			$uid1 = $chatBot->get_uid($names[1]);
			$uid2 = $chatBot->get_uid($names[2]);
			if (!$uid1) {
				$msg = "Player <highlight>$name_alt<end> does not exist.";
			}
			if (!$uid2) {
				$msg .= " Player <highlight>$name_main<end> does not exist.";
			}
			if ($uid1 && $uid2) {
				$db->query("SELECT * FROM alts WHERE alt = '$name_alt' AND main = '$name_main'");
				if($db->numrows() != 0) {
					$db->query("DELETE FROM alts WHERE main = '$name_main' AND alt = '$name_alt'");
					$msg = "<highlight>$name_alt<end> has been deleted from the alt list of <highlight>$name_main.<end>";
				} else {
					$msg = "Player <highlight>$name_alt<end> not listed as an alt of Player <highlight>$name_main<end>.  Please check the player's !alts listings.";
				}
			}
		}
	} else if ($names[1] == '' || $names[2] == '') {
		$msg = "Some information is missing. Please check <highlight>/tell <myname> <symbol>help altsadmin<end> for the correct syntax.";
	} else {
		$msg = "<highlight>/tell <myname> <symbol>help altsadmin<end> for the correct syntax.";
	}
}

$chatBot->send($msg, $sendto);

?>