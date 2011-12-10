<?php
$info = explode(" ", $message);
list($msg, $AttTim, $RechT) = $info;

if ((!$AttTim) || (!$RechT)) {
	$syntax_error = true;
} else {
	if ($RechT < 1200) {
		$AttCalc	= round(((($AttTim - ($RechT / 200)) )/0.02) + 87.5, 0);
	} else {
		$RechTk = $RechT - 1200;
		$AttCalc = round(((($AttTim - (1200/200) - ($RechTk / 200 / 6)))/0.02) + 87.5, 0);
	}

	$InitResult = $AttCalc;
	if( $InitResult < 0 ) $InitResult = 0;
	if( $InitResult > 100 ) $InitResult = 100;
		
	$Initatta1 = round((((100 - 87.5) * 0.02) - $AttTim) * (-200),0);
	if($Initatta1 > 1200) { $Initatta1 = round((((((100-87.5)*0.02)-$AttTim+6)*(-600)))+1200,0); }
	$Init1 = $Initatta1;
		
	$Initatta2 = round((((87.5-87.5)*0.02)-$AttTim)*(-200),0);
	if($Initatta2 > 1200) { $Initatta2 = round((((((87.5-87.5)*0.02)-$AttTim+6)*(-600)))+1200,0); }
	$Init2 = $Initatta2;
			
	$Initatta3 = round((((0-87.5)*0.02)-$AttTim)*(-200),0);
	if($Initatta3 > 1200) { $Initatta3 = round((((((0-87.5)*0.02)-$AttTim+6)*(-600)))+1200,0); }
	$Init3 = $Initatta3;
			
	$inside	= "Attack:<orange> ". $AttTim ." <end>second(s).\n";
	$inside	.= "Init Skill:<orange> ". $RechT ."<end>\n";
	$inside	.= "Def/Agg:<orange> ". $InitResult ."%<end>\n";
	$inside	.= "You must set your AGG bar at<orange> ". $InitResult ."% (". round($InitResult*8/100,2) .") <end>to instacast your nano.\n\n";
	$inside	.= "NanoC. Init needed to instacast at Full Agg:<orange> ". $Init1 ." <end>inits.\n";
	$inside	.= "NanoC. Init needed to instacast at neutral (88%bar):<orange> ". $Init2 ." <end>inits.\n";
	$inside	.= "NanoC. Init needed to instacast at Full Def:<orange> ". $Init3 ." <end>inits.";

	$windowlink = Text::make_blob("::Nano Init Results::", $inside);
	$chatBot->send($windowlink, $sendto);
}
?>