<?php
/*
** Description: Alt Char Handling
** Version: 1.0
*/

if (eregi("^alts add (.+)$", $message, $arr)) {


	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if ($db->numrows() == 0) {
		bot::send("You are not registered with this bot.",$sender);
		return;
	}


	$name = ucfirst(strtolower($arr[1]));
	$uid = AoChat::get_uid($arr[1]);
	if (!$uid) {
		$msg = "Player <highlight>$name<end> does not exist.";
	} else {
		$db->query("SELECT * FROM alts WHERE `alt` = '$name'");
		$row = $db->fObject();
		if ($row->alt == $name) {
			$msg = "Player <highlight>$name<end> is already registered as alt from <highlight>$row->main<end>.";
		} else {
			$db->query("SELECT * FROM alts WHERE `main` = '$name'");
			if ($db->numrows() != 0) {
				$msg = "Player <highlight>$name<end> is already registered as main from someone.";
			} else {
				//check added to make sure the $sender himself isn't already an alt
				$db->query("SELECT * FROM alts WHERE `alt` = '$sender'");
				$row = $db->fObject();
				if ($row->alt == $sender) {
					$db->query("INSERT INTO alts (`alt`, `main`) VALUES ('$name', '$row->main')");
					$msg = "<highlight>You<end> are already an alt of <highlight>$row->main<end>. <highlight>$name<end> has been registered as an alt of <highlight>$row->main<end>.";
				} else {
					$db->query("INSERT INTO alts (`alt`, `main`) VALUES ('$name', '$sender')");
					$msg = "<highlight>$name<end> has been registered as your alt. If your alt is under level 175, it may now register to receive OTNews messages.";
				}
			}
		}
	}
} else if (eregi("^alts (rem|del|remove|delete) (.+)$", $message, $arr)) {

	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if ($db->numrows() == 0){	
		bot::send("You are not registered with this bot.",$sender);
		return;
	}

	$name = ucfirst(strtolower($arr[2]));
	$uid = AoChat::get_uid($arr[2]);
	if (!$uid) {
		$msg = "Player <highlight>".$name."<end> does not exist.";
	} else {
		$db->query("SELECT * FROM alts WHERE `main` = '$sender' AND `alt` = '$name'");
		$row = $db->fObject();
		if ($row->main == $sender) {
			$db->query("DELETE FROM alts WHERE `main` = '$sender' AND `alt` = '$name'");
			$msg = "<highlight>$name<end> has been deleted from your alt list. (if alt was registered, it has been removed) ";
			$db->query("DELETE FROM spammembers WHERE name = '$name' ");
		} else {
			//sender was not found as a main.  checking if he himself is an alt and let him be able to modify his own alts list
			$db->query("SELECT * FROM alts WHERE `alt` = '$sender'");
			$row = $db->fObject();
			//retrieve his main, use the main's name to do searches and modifications with
			$main = $row->main;
			$db->query("SELECT * FROM alts WHERE main = '$main' AND alt = '$name'");
			if ($db->numrows() != 0) {
				$db->query("DELETE FROM alts WHERE main = '$main' AND alt = '$name'");
				$msg = "<highlight>$name<end> has been deleted from your ($main) alt list. (if alt was registered, it has been removed)";
				$db->query("DELETE FROM spammembers WHERE name = '$main' ");
			} else {
				$msg = "<highlight>$name<end> is not registered as your alt.";
			}
		}
	}
} else if (eregi("^alts (.+)$", $message, $arr)) {

	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if ($db->numrows() == 0) {
		bot::send("You are not registered with this bot.",$sender);
		return;
	}

	$check = FALSE;
	$name = ucfirst(strtolower($arr[1]));
	$uid = AoChat::get_uid($arr[1]);
	if (!$uid) {
		$msg = "Player <highlight>".$name."<end> does not exist.";
	} else {
		$main = false;
		// Check if sender is himelf the main
		$db->query("SELECT * FROM alts WHERE `main` = '$name'");
		if ($db->numrows() == 0) {
			// Check if sender is an alt
			$db->query("SELECT * FROM alts WHERE `alt` = '$name'");
			if ($db->numrows() == 0) {
				$msg = "No alts are registered for <highlight>$name<end>.";
			} else {
				$row = $db->fObject();
				$main = $row->main;
			}
		} else {
			$main = $name;
		}

		if ($sender == $main) $check = TRUE;

		// If a main was found create the list
		if ($main) {
			$list = "<header>::::: Alternative Character List :::::<end> \n \n";
			$list .= ":::::: Main Character\n";
			$list .= "<tab><tab>".bot::makeLink($main, "/tell ".$this->vars["name"]." whois $main", "chatcmd")." \n";
			$list .= ":::::: Alt Character(s)\n";
			$db->query("SELECT * FROM alts WHERE `main` = '$main'");
			while ($row = $db->fObject()) {
				$list .= "<tab><tab>".bot::makeLink($row->alt, "/tell ".$this->vars["name"]." whois $row->alt", "chatcmd")." \n";
				if ($sender == $row->alt) $check = TRUE;		
			}

			if ($this->admins[$sender]["level"] > 2) {
				$check = TRUE;
			}

			if ($check == FALSE) {
				$msg = "You are not authorised for that.";
			} else {
				$msg = bot::makeLink($main."`s Alts", $list);
			}
		}
	}
} else if (eregi("^alts$", $message)) {

	$db->query("SELECT * FROM spammembers WHERE `name` = '$sender'");
	if ($db->numrows() == 0){	
		bot::send("You are not registered with this bot.",$sender);
		return;
	}
	$main = false;
	// Check if $sender is himself the main
	$db->query("SELECT * FROM alts WHERE `main` = '$sender'");
	if ($db->numrows() == 0){
		// Check if $sender is an alt
		$db->query("SELECT * FROM alts WHERE `alt` = '$sender'");
		if ($db->numrows() == 0) {
			$msg = "No alts are registered for <highlight>$sender<end>.";
		} else {
			$row = $db->fObject();
			$main = $row->main;
		}
	} else {
		$main = $sender;
	}


	// If a main was found create the list
	if ($main) {
		$list = "<header>::::: Alternative Character List :::::<end> \n \n";
		$list .= ":::::: Main Character\n";
		$list .= "<tab><tab>".bot::makeLink($main, "/tell ".$this->vars["name"]." whois $main", "chatcmd")." \n";

		$list .= ":::::: Alt Character(s)\n";
		$db->query("SELECT * FROM alts WHERE `main` = '$main'");
		while ($row = $db->fObject()) {
			$list .= "<tab><tab>".bot::makeLink($row->alt, "/tell ".$this->vars["name"]." whois $row->alt", "chatcmd")." \n";
		}
		$msg = bot::makeLink($sender."`s Alts", $list);
	}
} else if (eregi("^altsadmin (.+)$", $message, $arr)) {
	if (eregi("^add (.+) (.+)$", $arr[1], $names)) {
		if ($names[1] != '' && $names[2] != '') {
			$name_alt = ucfirst(strtolower($names[1]));
			$name_main = ucfirst(strtolower($names[2]));
			$uid1 = AoChat::get_uid($names[1]);
			$uid2 = AoChat::get_uid($names[2]);
			if (!$uid1) {
				$msg = "Player <highlight>$name_alt<end> does not exist.";
			}
			if (!$uid2) {
				$msg .= " Player <highlight>$name_main<end> does not exist.";
			}
			if ($uid1 && $uid2) {
				$db->query("SELECT * FROM alts WHERE `alt` = '$name_alt'");
				$row = $db->fObject();
				if ($row->alt == $name_alt) {
					$msg = "Player <highlight>$name_alt<end> is already registered as alt from <highlight>$row->main<end>.";
				} else {
					$db->query("SELECT * FROM alts WHERE `main` = '$name_alt'");
					if	($db->numrows() != 0) {
						$msg = "Player <highlight>$name_alt<end> is already registered as main from someone.";
					} else {
						$db->query("INSERT INTO alts (`alt`, `main`) VALUES ('$name_alt', '$name_main')");
						$msg = "<highlight>$name_alt<end> has been registered as an alt of $name_main.";
					}
				}
			}
		}
	} else if (eregi("^rem (.+) (.+)$", $arr[1], $names)) {
		if ($names[1] != '' && $names[2] != '') {
			$name_alt = ucfirst(strtolower($names[1]));
			$name_main = ucfirst(strtolower($names[2]));
			$uid1 = AoChat::get_uid($names[1]);
			$uid2 = AoChat::get_uid($names[2]);
			if (!$uid1) {
				$msg = "Player <highlight>$name_alt<end> does not exist.";
			}
			if (!$uid2) {
				$msg .= " Player <highlight>$name_main<end> does not exist.";
			}
			if ($uid1 && $uid2) {
				$db->query("SELECT * FROM alts WHERE alt = '$name_alt' AND main = '$name_main'");
				if ($db->numrows() != 0) {
					$db->query("DELETE FROM alts WHERE main = '$name_main' AND alt = '$name_alt'");
					$db->query("DELETE FROM spammembers WHERE name = '$name_alt'");
					$msg = "<highlight>$name_alt<end> has been deleted from the alt list of <highlight>$name_main.<end> (if alt was registered, it has been removed)";
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

bot::send($msg, $sendto);

?>
