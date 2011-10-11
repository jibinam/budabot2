<?php
/* ******************************************** */
/* Author: C. Lohmann alias Derroylo(RK2)       */
/* Date(created): 24.02.2006                    */
/* Date(last modified): 26.02.2006              */
/* Project: Org/Raid Bot                        */
/* Description: Creates a Guardian Order        */
/* Version: 1.0                                 */
/* Part of: Guardian Module                     */
/* ******************************************** */

global $shadowbreed;
global $sblist;
global $caller;

if (count($shadowbreed) == 0) {
  	$msg = "No 215+ Solitus in chat.";
	bot::send($msg);
} else {
  	$sblist = "";
	$info  = "<header>::::: Info about Shadowbreed macro :::::<end>\n\n";
	$info .= "The bot has it´s own Shadowbreed macro to use it just do ";
	$info .= "<symbol>s in the chat. \n\n";
	$info .= "<a href='chatcmd:///macro SB_Macro /g <myname> <symbol>s'>Click here to make an sb macro </a>";
	$info = bot::makeLink("Info", $info);

  	//Create sb Order
	foreach($Shadowbreed as $key => $value) {
	  	if($caller == $key)
			$list[(sprintf("%03d", "300").$key)] = $key;
	  	elseif($shadowbreed[$key]["s"] == "ready")
			$list[(sprintf("%03d", (220 - $shadowbreed[$key]["lvl"])).$key)] = $key;
		else
			$list[(sprintf("%03d", "250").$key)] = $key;		
  	}

	$num = 0;
	ksort($list);
	reset($list);
  	$msg = "Shadowbreed order($info):";
	foreach($list as $player) {
	  	if($shadowbreed[$player]["s"] == "ready")
	  		$status = "<green>*Ready*<end>";
	  	elseif(($shadowbreed[$player]["s"] - time()) > 300)
	  		$status = "<red>Running<end>";
	  	else {
		    $rem = $shadowbreed[$player]["s"] - time();
			$mins = floor($rem / 60);
			$secs = $rem - ($mins * 60);
		    $status = "<orange>$mins:$secs<end>";
		}
		$num++;
		$msg .= " [$num. <highlight>$player<end> $status]";
        $sblist[] = $player;
        if($num >= $this->settings["shadowbreed_max"])
        	break;        
	}

  	//Send SBlist to all solitus
  	foreach($SBlist as $player) {
		bot::send($msg, $player);
  	}
	bot::send($msg);
}

?>