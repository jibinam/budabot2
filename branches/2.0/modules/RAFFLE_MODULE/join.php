<?php
if (preg_match("/^joinRaffle/i", $message, $arr)) {
	//check inprog and check not already in raffle
	if (!$chatBot->vars["Raffles"]["inprog"]) {
		$msg = "No raffle in progress.";
		$chatBot->send($msg, $sendto);
	} else if (array_search($sender, $chatBot->vars["Raffles"]["rafflees"]) !== false) {
		$msg = "You are already in the raffle.";
		$chatBot->send($msg, $sendto);
	} else {
		$chatBot->vars["Raffles"]["rafflees"][] = $sender;
		$msg = "$sender has entered the raffle.";
		$chatBot->send($msg, "org");
	}
}
?>