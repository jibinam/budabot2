<?php
if (preg_match("/^raffleStatus/i", $message, $arr)) {
	if ($chatBot->vars["Raffles"]["inprog"]) {
		$msg = "<white>Current Members:<end>";
		forEach ($chatBot->vars["Raffles"]["rafflees"] as $tempName) {
		   $msg .= "$tempName";
		}
		if (count($chatBot->vars["Raffles"]["rafflees"]) == 0) {
		   $msg .= "No entrants yet.";
		}
		
		$msg .= "

Click <a href='chatcmd:///tell <myname> joinRaffle'>here</a> to join the raffle!
Click <a href='chatcmd:///tell <myname> leaveRaffle'>here</a> if you wish to leave the raffle.";

		$tleft = $chatBot->vars["Raffles"]["time"] - time();
		$msg .= "\n\n Time left: $tleft seconds.";

		$link = Text::makeLink("Raffle Status", $msg);
		$chatBot->send($link, $sendto);
	} else {
		$msg = "A raffle is not in progress.";
		$chatBot->send($msg, $sendto);
	}
}
?>