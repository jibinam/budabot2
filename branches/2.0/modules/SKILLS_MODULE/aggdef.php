<?php
//Module based upon a RINGBOT module made by NoGoal (RK2)
//Modified for Budabot by Healnjoo (RK2)

$info = explode(" ", $message);
list($msg, $AttTim, $RechT, $InitS) = $info;

if ((!$AttTim) || (!$RechT) || (!$InitS)) {
	$syntax_error = true;
} else {
	if( $InitS < 1200 ) 
		{
		$AttCalc	= round(((($AttTim - ($InitS / 600)) - 1)/0.02) + 87.5, 0);
		$RechCalc	= round(((($RechT - ($InitS / 300)) - 1)/0.02) + 87.5, 0);
		}
	else 
		{
		$InitSk = $InitS - 1200;
		$AttCalc = round(((($AttTim - (1200/600) - ($InitSk / 600 / 3)) - 1)/0.02) + 87.5, 0);
		$RechCalc = round(((($RechT - (1200/300) - ($InitSk / 300 / 3)) - 1)/0.02) + 87.5, 0);
		}

	if( $AttCalc < $RechCalc ) $InitResult = $RechCalc;
		else { $InitResult = $AttCalc; }
		if( $InitResult < 0 ) $InitResult = 0;
		if( $InitResult > 100 ) $InitResult = 100;
				
	$Initatta1 = round((((100 - 87.5) * 0.02) + 1 - $AttTim) * (-600),0);
	$Initrech1 = round((((100-87.5)*0.02)+1-$RechT)*(-300),0);
		if($Initatta1 > 1200) { $Initatta1 = round((((((100-87.5)*0.02)+1-$AttTim+2)*(-1800)))+1200,0); }
		if($Initrech1 > 1200) { $Initrech1 = round((((((100-87.5)*0.02)+1-$AttTim+4)*(-900)))+1200,0); }
		if( $Initatta1 < $Initrech1 ) $Init1 = $Initrech1;
		else { $Init1 = $Initatta1; }
				
	$Initatta2 = round((((87.5-87.5)*0.02)+1-$AttTim)*(-600),0);
	$Initrech2 = round((((87.5-87.5)*0.02)+1-$RechT)*(-300),0);
		if($Initatta2 > 1200) { $Initatta2 = round((((((87.5-87.5)*0.02)+1-$AttTim+2)*(-1800)))+1200,0); }
		if($Initrech2 > 1200) { $Initrech2 = round((((((87.5-87.5)*0.02)+1-$AttTim+4)*(-900)))+1200,0); }
		if( $Initatta2 < $Initrech2 ) $Init2 = $Initrech2;
		else { $Init2 = $Initatta2; }
				
	$Initatta3 = round((((0-87.5)*0.02)+1-$AttTim)*(-600),0);
	$Initrech3 = round((((0-87.5)*0.02)+1-$RechT)*(-300),0);
		if($Initatta3 > 1200) { $Initatta3 = round((((((0-87.5)*0.02)+1-$AttTim+2)*(-1800)))+1200,0); }
		if($Initrech3 > 1200) { $Initrech3 = round((((((0-87.5)*0.02)+1-$AttTim+4)*(-900)))+1200,0); }
		if( $Initatta3 < $Initrech3 ) $Init3 = $Initrech3;
		else { $Init3 = $Initatta3; }

	$inside	= "Attack:<orange> ". $AttTim ." <end>second(s).\n";
	$inside	.= "Recharge: <orange>". $RechT ." <end>second(s).\n";
	$inside	.= "Init Skill: <orange>". $InitS ."<end>\n";
	$inside	.= "Def/Agg: <orange>". $InitResult ."%<end>\n";
	$inside	.= "You must set your AGG bar at <orange>". $InitResult ."% (". round($InitResult*8/100,2) .") <end>to wield your weapon at 1/1.\n\n";
	$inside	.= "Init needed for max speed at Full Agg: <orange>". $Init1 ." <end>inits.\n";
	$inside	.= "Init needed for max speed at neutral (88%bar): <orange>". $Init2 ." <end>inits.\n";
	$inside	.= "Init needed for max speed at Full Def: <orange>". $Init3 ." <end>inits.";	
	$inside .= "\n\nBased upon a RINGBOT module made by NoGoal(RK2).\n";
	$inside .= "Modified for Budabot by Healnjoo(RK2).";

	$windowlink = Text::makeBlob("::Your Agg/Def Settings::", $inside);
	$chatBot->send($windowlink, $sendto); 
} else {
	$syntax_error = true;
}

?>