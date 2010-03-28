<?php
/*
Written by Jaqueme
For Budabot
5/11/07
*/

If (ereg ("^research ([0-9]+)$",$message, $arr)) {
	$level = $arr[1];
	if ($level < 1 OR $level > 10) {
		$research = "<header>  ::::: RESEARCH QUERY <red>ERROR<end>  :::::  \n\n\n";
		$research .= "<red>Invalid Research Level Input.\n\n";
		$research .="<red>Valid reserch levels are from 1-10.";	
	}
	else {
		$sql = "SELECT * FROM research WHERE level = $level";
		$db->query($sql);
		$row = $db->fObject();
		
		$levelcap = $row->levelcap;
		$sk = $row->sk;
		$xp = $sk * 1000;
		$xp = number_format($xp);
		$sk = number_format($sk);
		$research = "<header>  ::::: XP/SK NEEDED FOR RESEARCH LEVELS  :::::<end>\n\n";
		$research .= "<green>You must be <blue>Level $levelcap<end> to reach <blue>Research Level $level<end>.\n";
		$research .= "You need <blue>$sk SK<end> to reach <blue>Research Level $level<end>.\n\n";
		$research .= "This equals <red>$xp XP.";
	}	
}
elseif (ereg ("^research ([0-9]+) ([0-9]+)$", $message, $arr)) {
	$lolevel = $arr[1];
	$hilevel = $arr[2];
	if ($lolevel < 1 OR $lolevel > 10 OR $hilevel < 1 OR $hilevel > 10) {
		$research = "<header>  ::::: RESEARCH QUERY <red>ERROR<end>  :::::  \n\n\n";
		$research .= "<red>Invalid Research Level Input.\n\n";
		$research .="<red>Valid reserch levels are from 1-10.";	
	}
	else {
		$sql = "SELECT 
			r1.level lolevel,
			r1.sk losk,
			r1.levelcap lolevelcap,
			r2.level hilevel,
			r2.sk hisk,
			r2.levelcap hilevelcap
		FROM
			research r1,
			research r2
		WHERE
			r1.level = $lolevel AND r2.level = $hilevel";
		$db->query($sql);
		$row = $db->fobject();
		$range = $row->hisk - $row->losk;
		$xp = number_format($range * 1000);
		$range = number_format($range);
		$research = "<header>  ::::: XP/SK NEEDED FOR RESEARCH LEVELS  :::::<end>\n\n";
		$research .= "<green>You must be <blue>Level $row->hilevelcap<end> to reach <blue>Research Level $row->hilevel.<end>\n";
		$research .= "It takes <blue>$range SK<end> to go from <blue>Research Level $row->lolevel<end> to <blue>Research Level $row->hilevel<end>.\n\n";
		$research .= "This equals <red>$xp XP.";
	}
}
else {
	$research = "<header>  ::::: SK NEEDED FOR RESEARCH LEVELS  :::::<end>\n\n";
	$research .= "<red> Invalid sreach criteria entered.  Please enter a required Level or Level Range.";
}	
$research = bot::makeLink("Research", $research);

if($type == "msg")
bot::send($research, $sender);
elseif($type == "all")
bot::send($research);
else
bot::send($research,"guild");
?>