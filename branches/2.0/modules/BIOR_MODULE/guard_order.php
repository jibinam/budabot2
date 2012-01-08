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

global $guard;
global $glist;
global $caller;

if (count($guard) == 0) {
  	$msg = "No 205+ Soldiers in chat.";
} else {
  	$glist = "";
	$info .= "The bot has it's own Guardian macro to use it just do ";
	$info .= "<symbol>g in the chat. \n\n";
	$info .= "<a href='chatcmd:///macro G_Macro /g <myname> <symbol>g'>Click here to make an G macro </a>";
	$info = Text::make_blob("Info about Guardian macro", $info);

  	//Create g Order
	forEach ($guard as $key => $value) {
	  	if ($caller == $key) {
			$list[(sprintf("%03d", "300").$key)] = $key;
	  	} else if ($guard[$key]["g"] == "ready") {
			$list[(sprintf("%03d", (220 - $guard[$key]["lvl"])).$key)] = $key;
		} else {
			$list[(sprintf("%03d", "250").$key)] = $key;
		}
  	}

	$num = 0;
	ksort($list);
	reset($list);
  	$msg = "Guardian Order($info):";
	forEach ($list as $player) {
	  	if ($guard[$player]["g"] == "ready") {
	  		$status = "<green>*Ready*<end>";
	  	} else if (($guard[$player]["g"] - time()) > 300) {
	  		$status = "<red>Running<end>";
	  	} else {
		    $rem = $guard[$player]["g"] - time();
			$mins = floor($rem / 60);
			$secs = $rem - ($mins * 60);
		    $status = "<orange>$mins:$secs<end>";
		}
		$num++;
		$msg .= " [$num. <highlight>$player<end> $status]";
        $glist[] = $player;
        if ($num >= Settings::get("guard_max")) {
        	break;
		}
	}

  	//Send Glist to all soldiers
  	forEach ($glist as $player) {
		$chatBot->send($msg, $player);
  	}
}
$chatBot->send($msg, $sendto);
?>