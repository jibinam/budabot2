<?php
/*
 ** Author: Mindrila (RK1)
 ** Description: Function file for the ONLINE_MODULE
 ** Version: 1.0
 **
 ** Under BudaBot's license.
 */

function online($type, $sender, $sendto, &$bot, $prof = "all") {
	$db = db::get_instance();

	$list = "";
	if ($bot->settings["relaydb"]) {
		if ($prof == "all") {
			$db->query("SELECT * FROM guild_chatlist_<myname> g LEFT JOIN players p ON g.name = p.name UNION ALL SELECT * FROM guild_chatlist_".strtolower($bot->settings["relaydb"])." g LEFT JOIN players p ON g.name = p.name ORDER BY `profession`, `level` DESC");
		} else {
			$db->query("SELECT * FROM guild_chatlist_<myname> g LEFT JOIN players p ON g.name = p.name WHERE `profession` = '$prof' UNION ALL SELECT * FROM guild_chatlist_".strtolower($bot->settings["relaydb"])." g LEFT JOIN players p ON g.name = p.name WHERE `profession` = '$prof'");
		}
	} else {
		if ($prof == "all") {
			$db->query("SELECT * FROM guild_chatlist_<myname> g LEFT JOIN players p ON g.name = p.name ORDER BY `profession`, `level` DESC");
		} else {
			$db->query("SELECT * FROM guild_chatlist_<myname> g LEFT JOIN players p ON g.name = p.name WHERE `profession` = '$prof'");
		}
	}

	$oldprof = "";
	$numonline = $db->numrows();
	if ($numonline == 1) {
		$list .= "<header>::::: 1 member online :::::<end>\n";
	} else {
		$list .= "<header>::::: $numonline members online :::::<end>\n";
	}
	$data = $db->fObject("all");
	// create the list with alts shown
	createList($data, $sender, $list, $type, $bot, true);

	// Private Channel Part
	if ($bot->settings["relaydb"]) {
		if ($prof == "all") {
			$db->query("SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name UNION ALL SELECT * FROM priv_chatlist_".strtolower($bot->settings["relaydb"])." p1 LEFT JOIN players p2 ON p1.name = p2.name ORDER BY `profession`, `level` DESC");
		} else {
			$db->query("SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name UNION ALL SELECT * FROM priv_chatlist_".strtolower($bot->settings["relaydb"])." p1 LEFT JOIN players p2 ON p1.name = p2.name WHERE `profession` = '$prof' ORDER BY `level` DESC");
		}
	} else {
		if ($prof == "all") {
			$db->query("SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name ORDER BY `profession`, `level` DESC");
		} else {
			$db->query("SELECT * FROM priv_chatlist_<myname> p1 LEFT JOIN players p2 ON p1.name = p2.name WHERE `profession` = '$prof' ORDER BY `level` DESC");
		}
	}

	$numguest = $db->numrows();
	if ($numguest == 1) {
		$list .= "\n\n<highlight><u>1 User in Private Channel<end></u>\n";
	} else {
		$list .= "\n\n<highlight><u>$numguest Users in Private Channel<end></u>\n";
	}
	$data = $db->fObject("all");
	// create the list of guests, without showing alts
	createList($data, $sender, $list, $type, $bot);
	$numonline += $numguest;

	$msg .= "{$numonline} member(s) online";

	// BBIN part
	if ($bot->settings["bbin_status"] == 1) {
		// members
		$db->query("SELECT * FROM bbin_chatlist_<myname> WHERE (`guest` = 0) ORDER BY `profession`, `level` DESC");
		$numbbinmembers = $db->numrows();
		$data = $db->fObject("all");
		if ($numbbinmembers == 1) {
			$list .= "\n\n<highlight><u>1 member in BBIN<end></u>\n";
		} else {
			$list .= "\n\n<highlight><u>$numbbinmembers members in BBIN<end></u>\n";
		}
		createList($data, $sender, $list, $type, $bot);
		
		// guests
		$db->query("SELECT * FROM bbin_chatlist_<myname> WHERE (`guest` = 1) ORDER BY `profession`, `level` DESC");
		$numbbinguests = $db->numrows();
		$data = $db->fObject("all");
		if ($numbbinguests == 1) {
			$list .= "\n\n<highlight><u>1 guest in BBIN<end></u>\n";
		} else {
			$list .= "\n\n<highlight><u>$numbbinguests guests in BBIN<end></u>\n";
		}
		createList($data, $sender, $list, $type, $bot);
		
		$numonline += $numbbinguests + $numbbinmembers;
		
		$msg .= " <green>BBIN<end>:".($numbbinguests + $numbbinmembers)." online";
	}

	return array ($numonline, $msg, $list);

}

function createList(&$data, &$sender, &$list, &$type, &$bot, $show_alts = false) {
	$db = db::get_instance();

	$oldprof = "";
	forEach ($data as $row) {
		$name = bot::makeLink($row->name, "/tell $row->name", "chatcmd");
		 
		if ($row->profession == "") {
			$row->profession = "Unknown";
		}
		
		if ($oldprof != $row->profession) {
			if($bot->settings["fancy_online"] == 0) {
				// old style delimiters
				$list .= "\n<tab><highlight>$row->profession<end>\n";
				$oldprof = $row->profession;
			} else {
				// fancy delimiters
				$list .= "<br><img src=tdb://id:GFX_GUI_FRIENDLIST_SPLITTER><br>";
				if ($bot->settings["icon_fancy_online"] == 1) {
					if($row->profession == "Adventurer")
						$list .= "<img src=rdb://84203>";
					elseif($row->profession == "Agent")
						$list .= "<img src=rdb://16186>";
					elseif($row->profession == "Bureaucrat")
						$list .= "<img src=rdb://46271>";
					elseif($row->profession == "Doctor")
						$list .= "<img src=rdb://44235>";
					elseif($row->profession == "Enforcer")
						$list .= "<img src=rdb://117926>";
					elseif($row->profession == "Engineer")
						$list .= "<img src=rdb://16307>";
					elseif($row->profession == "Fixer")
						$list .= "<img src=rdb://16300>";
					elseif($row->profession == "Keeper")
						$list .= "<img src=rdb://38911>";
					elseif($row->profession == "Martial Artist")
						$list .= "<img src=rdb://16289>";
					elseif($row->profession == "Meta-Physicist")
						$list .= "<img src=rdb://16283>";
					elseif($row->profession == "Nano-Technician")
						$list .= "<img src=rdb://45190>";
					elseif($row->profession == "Soldier")
						$list .= "<img src=rdb://16195>";
					elseif($row->profession == "Shade")
						$list .= "<img src=rdb://39290>";
					elseif($row->profession == "Trader")
						$list .= "<img src=rdb://118049>";
				}
				$list .= " <highlight>$row->profession<end>";
				$oldprof = $row->profession;

				$list .= "<br><img src=tdb://id:GFX_GUI_FRIENDLIST_SPLITTER><br>";
			}
		}

		if ($row->afk == "kiting") {
			$afk = " <highlight>::<end> <red>KITING<end>";
		} else if ($row->afk != "0") {
			$afk = " <highlight>::<end> <red>AFK<end>";
		} else {
			$afk = "";
		}

		if ($show_alts == true) {
			$db->query("SELECT * FROM alts WHERE `alt` = '$row->name'");
			if ($db->numrows() == 0) {
				$alt = "<highlight>::<end> <a href='chatcmd:///tell <myname> alts $row->name'>Alts</a>";
			} else {
				$row1 = $db->fObject();
				$alt = "<highlight>::<end> <a href='chatcmd:///tell <myname> alts $row->name'>Alts of $row1->main</a>";
			}
				
			if ($row->guild == "") {
				$guild = "Not in a guild";
			} else {
				$guild = $row->guild." (<highlight>$row->guild_rank<end>)";
			}
			$list .= "<tab><tab><highlight>$name<end> (Lvl $row->level/<green>$row->ai_level<end>) <highlight>::<end> $guild$afk $alt\n";
		} else {
			if ($row->guild == "") {
				$guild = "Not in a guild";
			} else {
				$guild = $row->guild;
			}
			$list .= "<tab><tab><highlight>$name<end> (Lvl $row->level/<green>$row->ai_level<end>) <highlight>::<end> $guild$afk\n";
		}
	}
}

?>