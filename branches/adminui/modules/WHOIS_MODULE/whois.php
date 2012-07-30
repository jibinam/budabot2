<?php

if (!function_exists('getNameHistory')) {
	function getNameHistory($charid, $dimension) {
		$chatBot = Registry::getInstance('chatBot');
		$db = Registry::getInstance('db');

		$sql = "SELECT * FROM name_history WHERE charid = ? AND dimension = ? ORDER BY dt DESC";
		$data = $db->query($sql, $charid, $dimension);

		$blob = "<header> :::::: Name History :::::: <end>\n\n";
		if (count($data) > 0) {
			forEach ($data as $row) {
				$blob .= "<green>{$row->name}<end> " . date(Util::DATETIME, $row->dt) . "\n";
			}
		} else {
			$blob .= "No name history available\n";
		}

		return $blob;
	}
}

if (preg_match("/^whois (.+)$/i", $message, $arr)) {
	$name = ucfirst(strtolower($arr[1]));
    $uid = $chatBot->get_uid($name);
    if ($uid) {
		$lookupNameLink = Text::make_chatcmd("Lookup", "/tell <myname> lookup $name");
		$lookupCharIdLink = Text::make_chatcmd("Lookup", "/tell <myname> lookup $uid");
        $whois = Player::get_by_name($name);
        if ($whois === null) {
			$blob = "<orange>Note: Could not retrieve detailed info for character.<end>\n\n";
	        $blob .= "Name: <highlight>{$name}<end> {$lookupNameLink}\n";
			$blob .= "Character ID: <highlight>{$uid}<end> {$lookupCharIdLink}\n\n";
			$blob .= "<pagebreak>" . getNameHistory($uid, $chatBot->vars['dimension']);
        	
			$msg = Text::make_blob("Basic Info for $name", $blob);
        } else {
	        $blob = "Name: <highlight>{$whois->firstname} \"{$name}\" {$whois->lastname}<end> {$lookupNameLink}\n";
			if ($whois->guild) {
				$blob .= "Guild: <highlight>{$whois->guild} ({$whois->guild_id})<end>\n";
				$blob .= "Guild Rank: <highlight>{$whois->guild_rank} ({$whois->guild_rank_id})<end>\n";
			}
			$blob .= "Breed: <highlight>{$whois->breed}<end>\n";
			$blob .= "Gender: <highlight>{$whois->gender}<end>\n";
			$blob .= "Profession: <highlight>{$whois->profession} ({$whois->prof_title})<end>\n";
			$blob .= "Level: <highlight>{$whois->level}<end>\n";
			$blob .= "AI Level: <highlight>{$whois->ai_level} ({$whois->ai_rank})<end>\n";
			$blob .= "Faction: <highlight>{$whois->faction}<end>\n";
			$blob .= "Character ID: <highlight>{$whois->charid}<end> {$lookupCharIdLink}\n\n";
			
			$blob .= "Source: $whois->source\n\n";
			
			$blob .= "<pagebreak>" . getNameHistory($uid, $chatBot->vars['dimension']);

			$blob .= "\n<pagebreak><header> :::::: Options :::::: <end>\n\n";
			
	        $blob .= Text::make_chatcmd('History', "/tell <myname> history $name") . "\n";
	        $blob .= Text::make_chatcmd('Online Status', "/tell <myname> is $name") . "\n";
	        if (isset($whois->guild_id)) {
		        $blob .= Text::make_chatcmd('Whoisorg', "/tell <myname> whoisorg $whois->guild_id") . "\n";
				$blob .= Text::make_chatcmd('Orglist', "/tell <myname> orglist $whois->guild_id") . "\n";
			}
			
	        $msg = Player::get_info($whois) . " :: " . Text::make_blob("More Info", $blob, "Detailed Info for {$name}");

			$altInfo = Alts::get_alt_info($name);
			if (count($altInfo->alts) > 0) {
				$msg .= " :: " . $altInfo->get_alts_blob(false, true);
			}
	    }
    } else {
        $msg = "Character <highlight>{$name}<end> does not exist.";
	}

    $sendto->reply($msg);
} else if (preg_match("/^whoisall (.+)$/i", $message, $arr)) {
    $name = ucfirst(strtolower($arr[1]));
    for ($i = 1; $i <= 2; $i ++) {
        if ($i == 1) {
            $server = "Atlantean";
        } else if ($i == 2) {
            $server = "Rimor";
		}

        $whois = Player::lookup($name, $i);
        if ($whois !== null) {
            $msg = Player::get_info($whois);

	        $blob = "Name: <highlight>{$whois->firstname} \"{$name}\" {$whois->lastname}<end>\n";
			if ($whois->guild) {
				$blob .= "Guild: <highlight>{$whois->guild} ({$whois->guild_id})<end>\n";
				$blob .= "Guild Rank: <highlight>{$whois->guild_rank} ({$whois->guild_rank_id})<end>\n";
			}
			$blob .= "Breed: <highlight>{$whois->breed}<end>\n";
			$blob .= "Gender: <highlight>{$whois->gender}<end>\n";
			$blob .= "Profession: <highlight>{$whois->profession} ({$whois->prof_title})<end>\n";
			$blob .= "Level: <highlight>{$whois->level}<end>\n";
			$blob .= "AI Level: <highlight>{$whois->ai_level} ({$whois->ai_rank})<end>\n";
			$blob .= "Faction: <highlight>{$whois->faction}<end>\n\n";
			
			$blob .= "Source: $whois->source\n\n";

			$blob .= "<pagebreak><header> :::::: Options :::::: <end>\n\n";

            $blob .= "<a href='chatcmd:///tell <myname> history {$name} {$i}'>History</a>\n";
			
            $msg .= " :: ".Text::make_blob("More info", $blob, "Detailed Info for {$name}");
            $msg = "<highlight>Server $server:<end> ".$msg;
        } else {
            $msg = "Server $server: Character <highlight>{$name}<end> does not exist.";
		}

        $sendto->reply($msg);
    }
} else {
	$syntax_error = true;
}

?>