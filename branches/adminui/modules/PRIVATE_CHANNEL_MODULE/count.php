<?php

if (preg_match("/^count (level|lvl)$/i", $message, $arr)) {
	$tl1 = 0;
	$tl2 = 0;
	$tl3 = 0;
	$tl4 = 0;
	$tl5 = 0;
	$tl6 = 0;
	$tl7 = 0;
	
	$data = $db->query("SELECT * FROM online o LEFT JOIN players p ON (o.name = p.name AND p.dimension = '<dim>') WHERE added_by = '<myname>' AND channel_type = 'priv'");
	$numonline = count($data);
    forEach ($data as $row) {
      	if ($row->level > 1 && $row->level <= 14) {
      		$tl1++;
      	} else if ($row->level >= 15 && $row->level <= 49) {
      		$tl2++;
      	} else if ($row->level >= 50 && $row->level <= 99) {
      		$tl3++;
      	} else if ($row->level >= 100 && $row->level <= 149) {
      		$tl4++;
      	} else if ($row->level >= 150 && $row->level <= 189) {
      		$tl5++;
      	} else if ($row->level >= 190 && $row->level <= 204) {
      		$tl6++;
      	} else if ($row->level >= 205 && $row->level <= 220) {
      		$tl7++;
		}
    }
    $msg = "<highlight>$numonline<end> in total: TL1 <highlight>$tl1<end>, TL2 <highlight>$tl2<end>, TL3 <highlight>$tl3<end>, TL4 <highlight>$tl4<end>, TL5 <highlight>$tl5<end>, TL6 <highlight>$tl6<end>, TL7 <highlight>$tl7<end>";
    $sendto->reply($msg);
} else if (preg_match("/^count (all|prof)$/i", $message)) {
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
	$online["Nano-Technician"] = 0;
	$online["Soldier"] = 0;
	$online["Trader"] = 0;
	$online["Shade"] = 0;

	$data = $db->query("SELECT count(*) AS count, profession FROM online o LEFT JOIN players p ON (o.name = p.name AND p.dimension = '<dim>') WHERE added_by = '<myname>' AND channel_type = 'priv' GROUP BY `profession`");
	$numonline = count($data);
	$msg = "<highlight>$numonline<end> in total: ";	

    forEach ($data as $row) {
   	    $online[$row->profession] = $row->count;
	}

	/*
	forEach ($online as $prof => $count) {
		if ($count > 0) {
			$msg .= "<highlight>{$count}<end> {$prof}, ";
		}
	}
	*/

    $msg .= "<highlight>".$online['Adventurer']."<end> Adv, <highlight>".$online['Agent']."<end> Agent, <highlight>".$online['Bureaucrat']."<end> Crat, <highlight>".$online['Doctor']."<end> Doc, <highlight>".$online['Enforcer']."<end> Enf, <highlight>".$online['Engineer']."<end> Eng, <highlight>".$online['Fixer']."<end> Fix, <highlight>".$online['Keeper']."<end> Keeper, <highlight>".$online['Martial Artist']."<end> MA, <highlight>".$online['Meta-Physicist']."<end> MP, <highlight>".$online['Nano-Technician']."<end> NT, <highlight>".$online['Soldier']."<end> Sol, <highlight>".$online['Shade']."<end> Shade, <highlight>".$online['Trader']."<end> Trader";

  	$sendto->reply($msg);
} else if (preg_match("/^count org$/i", $message, $arr)) {
	$sql = "SELECT * FROM online WHERE added_by = '<myname>' AND channel_type = 'priv'";
	$data = $db->query($sql);
	$numonline = count($data);
	
	if ($numonline == 0) {
		$msg = "No players in channel.";
		$sendto->reply($msg);
		return;
	}

	$sql = "SELECT `guild`, count(*) AS cnt, AVG(level) AS avg_level FROM online o LEFT JOIN players p ON (o.name = p.name AND p.dimension = '<dim>') WHERE added_by = '<myname>' AND channel_type = 'priv' AND `guild` <> '' GROUP BY `guild` ORDER BY `cnt` DESC, `avg_level` DESC";
	$data = $db->query($sql);
	$numorgs = count($data);
	
	$blob = '';
    forEach ($data as $row) {
		$percent = round($row->cnt / $numonline, 2) * 100;
		$avg_level = round($row->avg_level, 1);
   	    $blob .= "{$percent}% {$row->guild} - {$row->cnt} member(s), average level {$avg_level}\n";
	}
	
	$msg = Text::make_blob("Organizations ($numorgs)", $blob);
	$sendto->reply($msg);
} else if (preg_match("/^count (.*)$/i", $message, $arr)) {
    switch (strtolower($arr[1])) {
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
		default:
			$msg = "Please choose one of these professions: adv, agent, crat, doc, enf, eng, fix, keep, ma, mp, nt, sol, shade, trader or all";
			$sendto->reply($msg);
			return;
    }
   
	$data = $db->query("SELECT * FROM online o LEFT JOIN players p ON (o.name = p.name AND p.dimension = '<dim>') WHERE added_by = '<myname>' AND channel_type = 'priv' AND `profession` = ? ORDER BY `level`", $prof);
    $numonline = count($data);
    $msg = "<highlight>$numonline<end> $prof:";

    forEach ($data as $row) {
		if ($row->afk != "") {
            $afk = "<red>*AFK*<end>";
        } else {
            $afk = "";
		}
        $msg .= " [<highlight>$row->name<end> - ".$row->level.$afk."]";
    }
    $sendto->reply($msg);  	
} else {
	$syntax_error = true;
}

?>