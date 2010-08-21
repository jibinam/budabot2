<?php
/*
 ** Author: Mindrila (RK1)
 ** Credits: Legendadv (RK2)
 ** BUDABOT IRC NETWORK MODULE
 ** Version = 0.1
 ** Developed for: Budabot(http://budabot.com)
 **
 */

/*
 * This is the main parse function for incoming messages other than
 * IRC related stuff
 */
function parse_incoming_bbin($bbinmsg, $nick, &$bot) {
	global $db;
	global $bbin_socket;

	if (preg_match("/^\[BBIN:LOGON:(.*?),(.),(.)\]/",$bbinmsg,$arr)) {
		// a user logged on somewhere in the network
		// first argument is name, second is dimension, third indicates a guest
		$name = $arr[1];
		$servernum = $arr[2];
		$guest = $arr[3];

		// get character informations
		$character = new WhoisXML($name, $servernum);

		// add user to bbin_chatlist_<myname>
		$db->query("INSERT INTO bbin_chatlist_<myname> (`name`, `faction`, `profession`, `guild`, `breed`, `level`, `ai_level`, `guest`, `dimension`, `ircrelay`) ".
				"VALUES ('$name', '$character->faction', '$character->prof', '$character->org', '$character->breed', '$character->level', '$character->ai_level', $guest, $servernum, '$nick')");

		// send notification to channels
		$msg = "<highlight>$name<end> (<highlight>{$character->level}<end>/<green>{$character->ai_level}<end>, <highlight>{$character->prof}<end>, $character->faction)";
		if ($character->org != "") {
			$msg .=	" {$character->rank} of {$character->org}";
		}
		$msg .= " has joined the network";
		if ($guest == 1) {
			$msg .= " as a guest";
		}
		$msg .= ".";

		if ($bot->vars['my guild'] != "") {
			$bot->send("<yellow>[BBIN]<end> $msg","guild",true);
		}
		if ($bot->vars['my guild'] == "" || $bot->settings["guest_relay"] == 1) {
			$bot->send("<yellow>[BBIN]<end> $msg","priv",true);
		}

	} else if (preg_match("/^\[BBIN:LOGOFF:(.*?),(.),(.)\]/",$bbinmsg,$arr)) {
		// a user logged off somewhere in the network
		$name = $arr[1];
		$servernum = $arr[2];
		$guest = $arr[3];

		// delete user from online table
		$db->query("DELETE FROM bbin_chatlist_<myname> WHERE (`name` = '$name') AND (`ircrelay` = '$nick')");

		// send notification to channels
		$msg = "";
		if ($guest == 1) {
			$msg = "Our guest ";
		}
		$msg .= "<highlight>$name<end> has left the network.";


		if($bot->vars['my guild'] != "") {
			$bot->send("<yellow>[BBIN]<end> $msg","guild",true);
		}
		if($bot->vars['my guild'] == "" || $bot->settings["guest_relay"] == 1) {
			$bot->send("<yellow>[BBIN]<end> $msg","priv",true);
		}

	} else if (preg_match("/^\[BBIN:SYNCHRONIZE\]/",$bbinmsg)) {
		// a new bot joined and requested a full online synchronization

		// drop existing data
		$db->query("DELETE FROM bbin_chatlist_<myname>");

		// send actual online members

		$msg = "[BBIN:ONLINELIST:".$bot->vars["dimension"].":";
		$data = $db->query("SELECT * FROM guild_chatlist_<myname>");
		$numrows = $db->numrows();

		forEach ($data as $row) {
			// add members
			$msg .= $row->name . ",0,";
		}

		$data = $db->query("SELECT * FROM priv_chatlist_<myname>");
		$numrows += $db->numrows();

		forEach ($data as $row) {
			// add guests
			$msg .= $row->name . ",1,";
		}
		if ($numrows != 0) {
			// remove trailing , if there is one
			$msg = substr($msg,0,strlen($msg)-1);
		}

		$msg .= "]";

		// send complete list back to bbin channel
		fputs($bbin_socket, "PRIVMSG ".$bot->settings['bbin_channel']." :$msg\n");

	} else if (preg_match("/^\[BBIN:ONLINELIST:(.):(.*?)\]/", $bbinmsg, $arr)) {
		// received a synchronization list
		
		// delete all buddies from that nick
		$db->query("DELETE FROM bbin_chatlist_<myname> WHERE `ircrelay` = '$nick'");
		
		// Format: [BBIN:ONLINELIST:dimension:name,isguest,name,isguest....]
		$dimension = $arr[1];
		$listplode = explode(',', $arr[2]);

		// listplode should be: {name,isguest,name,isguest ...}
		while (true) {
			// as using array_pop will lead to null some time,
			// this loop will exit when all chars are parsed
				
			// pop last value off array (isguest of last member)
			$isguest = array_pop($listplode);
				
			// pop next value off array (name of last member)
			$name = array_pop($listplode);
				
			if ($isguest == null || $name == null) {
				// we popped all items of the array, break
				break;
			}
				
			// get character data
			$character = new WhoisXML($name, $dimension);
				
			// add user to bbin_chatlist_<myname>
			$db->query("INSERT INTO bbin_chatlist_<myname> (`name`, `faction`, `profession`, `guild`, `breed`, `level`, `ai_level`, `guest`, `dimension`, `ircrelay`) ".
				"VALUES ('$name', '$character->faction', '$character->prof', '$character->org', '$character->breed', '$character->level', '$character->ai_level', $isguest, $dimension, '$nick')");
		}
	} else {
		// normal message
		if($bot->vars['my guild'] != "") {
			$bot->send("<yellow>[BBIN]<end> $bbinmsg","guild",true);
		}
		if ($bot->vars['my guild'] == "" || $bot->settings["guest_relay"] == 1) {
			$bot->send("<yellow>[BBIN]<end> $bbinmsg","priv",true);
		}
	}
}
?>
