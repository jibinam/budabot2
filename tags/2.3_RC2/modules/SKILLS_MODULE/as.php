<?php

list($command, $AttTim, $RechT, $InitS) = explode(" ", $message);

if ((!$AttTim) || (!$RechT) || (!$InitS)) {
	$syntax_error = true;
} else {
	list($cap, $ASCap) = cap_aimed_shot($AttTim, $RechT);
	
	$ASRech	= ceil(($RechT * 40) - ($InitS * 3 / 100) + $AttTim - 1);
	if ($ASRech < $cap) {
		$ASRech = $cap;
	}
	$MultiP	= round($InitS / 95,0);

	$blob = "<header> :::::: Aimed Shot Calculator :::::: <end>\n\n";
	$blob .= "Results:\n\n";
	$blob .= "Attack: <orange>". $AttTim ." <end>second(s).\n";
	$blob .= "Recharge: <orange>". $RechT ." <end>second(s).\n";
	$blob .= "AS Skill: <orange>". $InitS ."<end>\n\n";
	$blob .= "AS Multiplier:<orange> 1-". $MultiP ."x<end>\n";
	$blob .= "AS Recharge: <orange>". $ASRech ."<end> seconds.\n";
	$blob .= "With your weap, your AS recharge will cap at <orange>".$cap."<end>s.\n";
	$blob .= "You need <orange>".$ASCap."<end> AS skill to cap your recharge.";

	$msg = Text::make_blob("::Your Aimed Shot Results::", $blob);
	$chatBot->send($msg, $sendto);
}

?>